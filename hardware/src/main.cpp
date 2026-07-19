#include <Arduino.h>
#include <Wire.h>
#include <Adafruit_PN532.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

// ── Konfigurasi WiFi & Server ──────────────────────────────────────────────
const char* WIFI_SSID     = "Nidiooxx";
const char* WIFI_PASSWORD = "Hermanos";
const char* SERVER_URL    = "http://192.168.1.11:8000/api/card-check";

// ── Pin PN532 ──────────────────────────────────────────────────────────────
#define PN532_IRQ   4
#define PN532_RESET 5

Adafruit_PN532 nfc(PN532_IRQ, PN532_RESET);

// ── Fungsi koneksi WiFi ────────────────────────────────────────────────────
void connectWifi() {
    Serial.print("Connecting to WiFi: ");
    Serial.println(WIFI_SSID);
    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
    int attempts = 0;
    while (WiFi.status() != WL_CONNECTED && attempts < 20) {
        delay(500);
        Serial.print(".");
        attempts++;
    }
    Serial.println();
    if (WiFi.status() == WL_CONNECTED) {
        Serial.print("Connected! IP: ");
        Serial.println(WiFi.localIP());
    } else {
        Serial.println("GAGAL konek WiFi! Cek SSID dan password.");
    }
}

// ── Fungsi kirim UID ke server ─────────────────────────────────────────────
void sendToServer(String uid) {
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("[WiFi] Disconnected, reconnecting...");
        connectWifi();
    }

    HTTPClient http;
    http.begin(SERVER_URL);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("Accept", "application/json");

    String body = "{\"rfid_uid\":\"" + uid + "\"}";
    int httpCode = http.POST(body);

    if (httpCode > 0) {
        String response = http.getString();
        Serial.println("[Server] Response: " + response);

        JsonDocument doc;
        deserializeJson(doc, response);

        if (doc["valid"].as<bool>()) {
            Serial.println("✅ VALID: " + String(doc["message"].as<const char*>()));
            Serial.print("   Saldo wallet: Rp ");
            Serial.println(doc["wallet_balance"].as<long>());
        } else {
            Serial.println("❌ DITOLAK: " + String(doc["message"].as<const char*>()));
        }
    } else {
        Serial.println("[Error] HTTP code: " + String(httpCode));
    }

    http.end();
}

// ── Setup ──────────────────────────────────────────────────────────────────
void setup() {
    Serial.begin(115200);
    delay(1000);
    Serial.println("\n=== SmartSpend NFC Reader ===");

    connectWifi();

    nfc.begin();
    uint32_t versiondata = nfc.getFirmwareVersion();
    if (!versiondata) {
        Serial.println("[Error] PN532 tidak terdeteksi! Cek wiring.");
        while (1);
    }

    Serial.print("[NFC] PN532 ditemukan, firmware v");
    Serial.println((versiondata >> 16) & 0xFF, DEC);

    nfc.SAMConfig();
    Serial.println("[NFC] Siap membaca kartu...\n");
}

// ── Loop ───────────────────────────────────────────────────────────────────
void loop() {
    uint8_t uid[7];
    uint8_t uidLength;

    bool cardDetected = nfc.readPassiveTargetID(PN532_MIFARE_ISO14443A, uid, &uidLength, 1000);

    if (cardDetected) {
        String uidStr = "";
        for (uint8_t i = 0; i < uidLength; i++) {
            if (uid[i] < 0x10) uidStr += "0";
            uidStr += String(uid[i], HEX);
        }
        uidStr.toUpperCase();

        Serial.println("──────────────────────────");
        Serial.println("[NFC] Kartu terdeteksi!");
        Serial.println("[NFC] UID: " + uidStr);

        sendToServer(uidStr);

        Serial.println("──────────────────────────\n");
        delay(3000);
    }
}
