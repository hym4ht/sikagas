#include <WiFi.h>              // Gunakan <ESP8266WiFi.h> jika Anda menggunakan ESP8266
#include <WiFiClientSecure.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>       // Pastikan Anda sudah menginstal library "ArduinoJson" di Arduino IDE

// ==========================================
// 1. KONFIGURASI WI-FI & API (HTTPS/HTTP)
// ==========================================
const char* ssid = "NAMA_WIFI_ANDA";         // Ganti dengan nama Wi-Fi Anda
const char* password = "PASSWORD_WIFI_ANDA"; // Ganti dengan password Wi-Fi Anda

// Alamat URL API Laravel di VPS Anda (Bisa menggunakan https:// atau http://)
// Contoh HTTPS: "https://domain-vps-anda.com/api/sensor" atau "https://IP_VPS:8037/api/sensor"
const char* serverUrl = "https://IP_VPS_ANDA:8037/api/sensor";

// ==========================================
// 2. PIN SENSOR (Sesuaikan dengan pin Anda)
// ==========================================
#define MQ_PIN 34       // Pin analog sensor gas MQ (ESP32: pin 34 / 35 / 36 / 39)
#define FLAME_PIN 23    // Pin digital sensor api (ESP32)
#define BUZZER_PIN 25   // Pin digital Buzzer alarm (jika ada)

// Selang waktu pengiriman data (dalam milidetik)
unsigned long previousMillis = 0;
const long interval = 5000; // Kirim data setiap 5 detik

void setup() {
  Serial.begin(115200);
  delay(1000);
  Serial.println("\n==========================================");
  Serial.println("🔥 Sistem Monitoring Gas & Kebocoran (SIKAGAS) 🔥");
  Serial.println("==========================================");

  // Inisialisasi pin input/output
  pinMode(MQ_PIN, INPUT);
  pinMode(FLAME_PIN, INPUT);
  pinMode(BUZZER_PIN, OUTPUT);
  digitalWrite(BUZZER_PIN, LOW); // Matikan buzzer di awal

  // Memulai koneksi Wi-Fi
  Serial.print("[Wi-Fi] Menghubungkan ke: ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("\n[Wi-Fi] Berhasil terhubung!");
  Serial.print("[Wi-Fi] IP Address local: ");
  Serial.println(WiFi.localIP());
  Serial.println("==========================================\n");
}

void loop() {
  unsigned long currentMillis = millis();

  // Pengiriman berkala tanpa blocking delay
  if (currentMillis - previousMillis >= interval) {
    previousMillis = currentMillis;

    // 1. Membaca Data dari Sensor Fisik
    int gasAnalog = analogRead(MQ_PIN); 
    int gasPPM = map(gasAnalog, 0, 4095, 0, 1000); // Skala pembacaan gas MQ

    int flameStatus = digitalRead(FLAME_PIN);
    bool apiDetected = (flameStatus == LOW); // Umumnya sensor api bernilai LOW saat deteksi api

    float suhu = 28.0 + (random(-10, 10) / 10.0); // Simulasi suhu sekitar 27-29 *C
    String aparStatus = apiDetected ? "AKTIF" : "SIAP";

    // Nyalakan Buzzer jika bahaya terdeteksi
    if (gasPPM > 150 || apiDetected) {
      digitalWrite(BUZZER_PIN, HIGH);
      Serial.println("[ALARM] Buzzer AKTIF! Kondisi Bahaya.");
    } else {
      digitalWrite(BUZZER_PIN, LOW);
    }

    // 2. Tampilkan Info Sensor ke Serial Monitor untuk Debugging Lokal
    Serial.println("------------------------------------------");
    Serial.println("[Sensor] Membaca data terbaru:");
    Serial.printf(" - Gas Raw (Analog) : %d\n", gasAnalog);
    Serial.printf(" - Gas Level (PPM)  : %d PPM\n", gasPPM);
    Serial.printf(" - Suhu Lingkungan  : %.2f *C\n", suhu);
    Serial.printf(" - Sensor Api (Pin) : %d (%s)\n", flameStatus, apiDetected ? "TERDETEKSI 🔥" : "AMAN");
    Serial.printf(" - Status APAR      : %s\n", aparStatus.c_str());

    // 3. Kirim data ke API Laravel di VPS
    if (WiFi.status() == WL_CONNECTED) {
      HTTPClient http;
      String urlStr = String(serverUrl);
      
      Serial.print("[HTTP] Memulai koneksi ke: ");
      Serial.println(serverUrl);

      // Cek apakah menggunakan HTTPS (SSL) atau HTTP biasa
      if (urlStr.startsWith("https")) {
        #if defined(ESP8266)
          // Implementasi HTTPS untuk ESP8266
          std::unique_ptr<BearSSL::WiFiClientSecure> client(new BearSSL::WiFiClientSecure);
          client->setInsecure(); // Mengabaikan verifikasi sertifikat SSL (Let's Encrypt / self-signed)
          http.begin(*client, serverUrl);
        #else
          // Implementasi HTTPS untuk ESP32
          WiFiClientSecure *client = new WiFiClientSecure;
          client->setInsecure(); // Mengabaikan verifikasi sertifikat SSL
          http.begin(*client, serverUrl);
        #endif
        Serial.println("[HTTP] Menggunakan koneksi HTTPS (SSL Secure)");
      } else {
        // Koneksi HTTP Biasa
        http.begin(serverUrl);
        Serial.println("[HTTP] Menggunakan koneksi HTTP standar");
      }

      http.addHeader("Content-Type", "application/json");

      // Membuat Payload JSON menggunakan ArduinoJson
      StaticJsonDocument<200> doc;
      doc["gas_level"] = gasPPM;
      doc["suhu"] = suhu;
      doc["api_detected"] = apiDetected;
      doc["apar_status"] = aparStatus;

      String jsonString;
      serializeJson(doc, jsonString);
      
      Serial.print("[HTTP] Mengirim data JSON: ");
      Serial.println(jsonString);

      // Mengirim HTTP POST
      int httpResponseCode = http.POST(jsonString);

      // Menangani respon balikan
      if (httpResponseCode > 0) {
        Serial.printf("[HTTP] Pengiriman sukses! Status Code: %d\n", httpResponseCode);
        String response = http.getString();
        Serial.print("[HTTP] Respon dari server: ");
        Serial.println(response);
      } else {
        Serial.printf("[HTTP] Pengiriman GAGAL! Error Code: %s\n", http.errorToString(httpResponseCode).c_str());
        Serial.println("[HTTP] Tips Debug:");
        Serial.println(" 1. Pastikan IP/domain VPS benar dan server Laravel sedang berjalan.");
        Serial.println(" 2. Jika menggunakan HTTPS, pastikan port 443 terbuka. Jika HTTP, pastikan port 8037 terbuka.");
        Serial.println(" 3. Tes URL API Anda menggunakan aplikasi Postman/HP terlebih dahulu.");
      }
      
      http.end(); // Tutup koneksi
    } else {
      Serial.println("[Wi-Fi] ERROR: Koneksi Wi-Fi terputus! Tidak bisa mengirim data.");
    }
    Serial.println("------------------------------------------");
  }
}
