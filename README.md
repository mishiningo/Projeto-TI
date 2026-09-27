# IoT Alarm System

An IoT alarm project built for the Internet Technologies course.

[Arduino](https://img.shields.io/badge/Arduino-00979D?logo=arduino&logoColor=white)

## About

The system consists of a web dashboard, an Arduino, a Raspberry Pi (or any other computer), and an Android camera.

The idea is to simulate the Arduino as the alarm unit, controlling a set of LEDs and a buzzer. It triggers when an ultrasonic sensor detects a door — or any other object — getting closer than it should. Once activated, the buzzer and LEDs alert everyone nearby.

## Components

### Web Dashboard
The dashboard supports three levels of privilege access, each with its own set of permissions over the alarm. For example, some roles can deactivate the alarm indefinitely, while others can only silence it for 30 seconds. The dashboard communicates with the Arduino over HTTPS.

| Access level | Permissions |
|---|---|
| _e.g. Admin_ | _Manage users_ |
| _e.g. Residente_ | _Deactivate alarm indefinitely_ |
| _e.g. Visitor_ | _Deactivate alarm for 30s only. Cannot see image history_ |

### Arduino
Acts as the alarm itself. Uses an ultrasonic sensor (_HC-SR04_) to detect proximity and triggers a buzzer and LEDs when an object(in the imagined scenario a door) gets too close.

### Raspberry Pi
Simulates a physical control bench. It displays the alarm's current state through a separate set of LEDs and can also activate or deactivate the alarm through a button. When the alarm fires, the Raspberry Pi signals the Android camera to capture a photo of the moment, then forwards that image to the dashboard so it's visible via the web.

### Android Camera
Captures an image the moment the alarm is triggered and sends it back to the raspberry, so it can forward it to be shown in the dashboard.

## Tech Stack

<!-- Fill in the actual technologies used -->
- **Arduino:** C++
- **Raspberry Pi:** Python
- **Web Dashboard:** HTML, CSS/Bootsrap, Javascript, PHP
- **Communication:** HTTPS HTTP

## Status

This was one of our first projects, so it's still fairly rudimentary. Feedback, suggestions, and improvement ideas are very welcome!

## Authors

[Leonardo Butschowitz](https://github.com/mishiningo)
[Tomás Paulino](https://github.com/TPaulino8)
