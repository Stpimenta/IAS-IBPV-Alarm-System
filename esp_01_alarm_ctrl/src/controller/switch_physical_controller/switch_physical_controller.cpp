#include "switch_physical_controller.h"

// Initially, relay is OFF
SwitchPhysicalController::SwitchPhysicalController(SwitchPhysical* s1, Relay_esp *relay, mqtt_protocols *mqttProtocols) {
  switch1 = s1;
  _relay = relay;
  _mqttProtocol = mqttProtocols;          
}

void SwitchPhysicalController::update() {
  static bool lastSwitch1State = false;
  bool currentSwitch1State = switch1->getState();


 // Check if state of any switch has changed
  if (currentSwitch1State != lastSwitch1State) {

    // Toggle the state if any switch is toggled
    if (currentSwitch1State) {
      _mqttProtocol->publishMessage("ALARM_OFF");
      
    } else {
      _mqttProtocol->publishMessage("ALARM_ON");   
    }

  }

  // Save current state for future comparisons
  lastSwitch1State = currentSwitch1State;

}
