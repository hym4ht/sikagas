#include <WiFi.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

const char* ssid      = "sikagas";
const char* password  = "12345678";

// ===== DOMAIN WEB =====
const char* serverURL      = "https://sikagas.web.id/api/sensor";
const char* aparCommandURL = "https://sikagas.web.id/api/apar/command";

LiquidCrystal_I2C lcd(0x27, 16, 2);

const int pinMQ2       = 34;
const int pinBuzzerLED = 27;
const int pinRelay     = 26;
const int pinMotorDC   = 33;

int  batasBahaya  = 2300;
int  batasWaspada = 1500;
bool statusBahayaTerkirim = false;

// Perintah manual terakhir dari web ("ON" / "OFF")
String perintahManual = "OFF";

// ============================================================
// Kirim data sensor ke backend
// ============================================================
void kirimKeWeb(int gasValue, String status, bool aparAktif, bool buzzerAktif) {
  if (WiFi.status() != WL_CONNECTED) return;

  HTTPClient http;
  WiFiClientSecure secureClient;
  secureClient.setInsecure(); // skip SSL verify (development)

  http.begin(secureClient, serverURL);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  http.addHeader("Accept", "application/json");

  String payload = "gas_value=" + String(gasValue)
                 + "&status=" + status
                 + "&apar_aktif=" + (aparAktif ? "1" : "0")
                 + "&buzzer_aktif=" + (buzzerAktif ? "1" : "0");

  int httpCode = http.POST(payload);
  Serial.println("HTTP Sensor Response: " + String(httpCode));
  http.end();
}

// ============================================================
// Polling perintah APAR dari backend
// ============================================================
void ambilPerintahApar() {
  if (WiFi.status() != WL_CONNECTED) return;

  HTTPClient http;
  WiFiClientSecure secureClient;
  secureClient.setInsecure();

  http.begin(secureClient, aparCommandURL);
  http.addHeader("Accept", "application/json");

  int httpCode = http.GET();

  if (httpCode == 200) {
    String body = http.getString();
    Serial.println("APAR Command Response: " + body);

    // Parse JSON sederhana: cari nilai "command":"ON" atau "command":"OFF"
    if (body.indexOf("\"ON\"") != -1) {
      perintahManual = "ON";
    } else {
      perintahManual = "OFF";
    }
  } else {
    Serial.println("Gagal ambil perintah APAR, HTTP: " + String(httpCode));
  }

  http.end();
}

// ============================================================
// Setup
// ============================================================
void setup() {
  Serial.begin(115200);
  Wire.begin(21, 22);

  pinMode(pinMQ2, INPUT);
  pinMode(pinBuzzerLED, OUTPUT);
  pinMode(pinRelay, OUTPUT);
  pinMode(pinMotorDC, OUTPUT);

  digitalWrite(pinBuzzerLED, LOW);
  digitalWrite(pinRelay, LOW);
  digitalWrite(pinMotorDC, LOW);

  lcd.init();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print(" DETEKSI GAS LPG");
  lcd.setCursor(0, 1);
  lcd.print(" Konek WiFi... ");

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  int timeout = 0;
  while (WiFi.status() != WL_CONNECTED && timeout < 20) {
    delay(500);
    Serial.print(".");
    timeout++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nWiFi Connected!");
    Serial.println("IP: " + WiFi.localIP().toString());
    lcd.setCursor(0, 1);
    lcd.print("  Pemanasan...  ");
    delay(30000);
  } else {
    Serial.println("\nGagal Konek WiFi");
    lcd.clear();
    lcd.print("WiFi GAGAL");
    lcd.setCursor(0, 1);
    lcd.print("Cek Hotspot!");
    while (1);
  }

  lcd.clear();
}

// ============================================================
// Timer
// ============================================================
unsigned long lastKirim        = 0;
unsigned long lastAmbilPerintah = 0;

const unsigned long intervalKirim        = 1000;  // kirim sensor tiap 1 detik
const unsigned long intervalAmbilPerintah = 5000;  // polling perintah tiap 5 detik

// ============================================================
// Loop Utama
// ============================================================
void loop() {
  unsigned long now = millis();

  // --- Polling perintah dari web ---
  if (now - lastAmbilPerintah >= intervalAmbilPerintah) {
    ambilPerintahApar();
    lastAmbilPerintah = now;
  }

  // --- Baca sensor gas ---
  int gasValue = analogRead(pinMQ2);
  Serial.println("Gas: " + String(gasValue) + " | Perintah: " + perintahManual);

  String statusSensor;
  bool aparAktif   = false;
  bool buzzerAktif = false;

  if (gasValue > batasBahaya) {
    // ===== BAHAYA — sensor override segalanya =====
    statusSensor = "BAHAYA";
    aparAktif    = true;
    buzzerAktif  = true;

    digitalWrite(pinBuzzerLED, HIGH);
    digitalWrite(pinRelay, HIGH);
    digitalWrite(pinMotorDC, HIGH);

    lcd.setCursor(0, 0);
    lcd.print("!!!! BAHAYA GAS !!");
    lcd.setCursor(0, 1);
    lcd.print("   APAR AKTIF    ");

    if (!statusBahayaTerkirim) {
      statusBahayaTerkirim = true;
    }

  } else if (gasValue > batasWaspada) {
    // ===== WASPADA =====
    statusSensor = "WASPADA";
    buzzerAktif  = true;

    // Perintah manual ON → aktifkan relay+motor walau waspada
    if (perintahManual == "ON") {
      aparAktif = true;
      digitalWrite(pinRelay, HIGH);
      digitalWrite(pinMotorDC, HIGH);
    } else {
      aparAktif = false;
      digitalWrite(pinRelay, HIGH);  // relay tetap aktif di waspada (blower/ventilasi)
      digitalWrite(pinMotorDC, LOW);
    }

    digitalWrite(pinBuzzerLED, (millis() / 400) % 2);

    lcd.setCursor(0, 0);
    lcd.print("!! WASPADA GAS !!");
    lcd.setCursor(0, 1);
    lcd.print("Gas: "); lcd.print(gasValue); lcd.print("   ");

    statusBahayaTerkirim = false;

  } else {
    // ===== AMAN — ikuti perintah manual =====
    statusSensor = "AMAN";

    if (perintahManual == "ON") {
      // Manual ON: aktifkan APAR walau sensor aman
      aparAktif    = true;
      buzzerAktif  = false;

      digitalWrite(pinBuzzerLED, LOW);
      digitalWrite(pinRelay, HIGH);
      digitalWrite(pinMotorDC, HIGH);

      lcd.setCursor(0, 0);
      lcd.print("  APAR: MANUAL  ");
      lcd.setCursor(0, 1);
      lcd.print("Gas: "); lcd.print(gasValue); lcd.print("   ");

    } else {
      // Manual OFF + sensor aman → semua mati
      aparAktif    = false;
      buzzerAktif  = false;

      digitalWrite(pinBuzzerLED, LOW);
      digitalWrite(pinRelay, LOW);
      digitalWrite(pinMotorDC, LOW);

      lcd.setCursor(0, 0);
      lcd.print("  STATUS: AMAN   ");
      lcd.setCursor(0, 1);
      lcd.print("Gas: "); lcd.print(gasValue); lcd.print("   ");
    }

    statusBahayaTerkirim = false;
  }

  // --- Kirim data sensor ke web ---
  if (now - lastKirim >= intervalKirim) {
    kirimKeWeb(gasValue, statusSensor, aparAktif, buzzerAktif);
    lastKirim = now;
  }

  delay(500);
}