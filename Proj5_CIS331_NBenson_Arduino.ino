//Arduino UNO
int led = 0;
void setup() {
  // put your setup code here, to run once:
  pinMode(11, OUTPUT);
  Serial.begin(115200);
}
 
void loop() {
  // put your main code here, to run repeatedly:
  if(Serial.available() > 0){
    led = Serial.parseInt();
    Serial.println(led);
  }
  if(led == 1){
      analogWrite(11, 50);
    }else{
      analogWrite(11,0);
    }
  delay(50);
}
