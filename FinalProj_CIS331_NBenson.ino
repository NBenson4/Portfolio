// Pin assignments
const int lightPin = A0;
const int soilPin = A1;
const int tempPin = A2;
const int greenLED = 3;
const int yellowLED = 4;
const int redLED = 5;

// Default thresholds
int lightThreshold = 500; // Adjust based on testing
int soilThreshold = 500;  // Adjust based on testing
int tempLow = 50;         // Adjust for Fahrenheit, e.g., 50°F
int tempHigh = 85;        // Adjust for Fahrenheit, e.g., 85°F

void setup() {
  pinMode(greenLED, OUTPUT);
  pinMode(yellowLED, OUTPUT);
  pinMode(redLED, OUTPUT);
  Serial.begin(9600);
  calibrateSensors(); // Optional, can be customized
}

void loop() {
  // Sensor readings
  int lightLevel = analogRead(lightPin);
  int soilMoisture = analogRead(soilPin);
  int tempValue = analogRead(tempPin);

  // Calculate temperature in Fahrenheit
  float temperature = calculateTemperature(tempValue);

  // Monitor conditions and control LEDs
  if (lightLevel < lightThreshold || soilMoisture < soilThreshold || temperature < tempLow || temperature > tempHigh) {
    digitalWrite(redLED, HIGH);   // Danger
    digitalWrite(yellowLED, LOW);
    digitalWrite(greenLED, LOW);
  } else if (soilMoisture < soilThreshold + 100) {
    digitalWrite(yellowLED, HIGH); // Caution
    digitalWrite(redLED, LOW);
    digitalWrite(greenLED, LOW);
  } else {
    digitalWrite(greenLED, HIGH);  // All Good
    digitalWrite(yellowLED, LOW);
    digitalWrite(redLED, LOW);
  }

  // Debugging info
  Serial.print("Light Level: ");
  Serial.println(lightLevel);

  Serial.print("Soil Moisture: ");
  Serial.println(soilMoisture);

  Serial.print("Temperature: ");
  Serial.print(temperature);
  Serial.println(" °F");

  delay(5000);
}

// Function to calculate temperature in Fahrenheit
float calculateTemperature(int analogValue) {
  float resistance = (1023.0 / analogValue - 1.0) * 10000; // Assuming 10kΩ pull-up resistor
  float tempC = 1.0 / (0.001129148 + (0.000234125 * log(resistance)) + (0.0000000876741 * pow(log(resistance), 3)));
  float tempF = (tempC - 273.15) * 9.0 / 5.0 + 32.0; // Convert Kelvin to Fahrenheit
  return tempF;
}

// Optional sensor calibration function
void calibrateSensors() {
  Serial.println("Calibrating sensors...");
  delay(5000); // Allow sensors to stabilize
  // You can use this to set dynamic thresholds if needed
}
