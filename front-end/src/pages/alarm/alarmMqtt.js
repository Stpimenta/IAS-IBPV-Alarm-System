const toggle = document.getElementById("toggle");
const statusText = document.getElementById("status");
toggle.disabled = true;

const errorMqtt = document.getElementById("error-mqtt");
const errorMqttText = document.getElementById("error-mqtt-text");
const errorMqttReconnectBtn = document.getElementById("error-mqtt-reconnect-btn");
const errorMqttClose = document.getElementById("error-mqtt-close");

const brokerUrl = "ws://pimenta.mercusysddns.com:9091";
const espId = "esp01_IBPV";
const topicSender = espId+"/sender"
const topicReceiver = espId+"/reciver"


const client = mqtt.connect(brokerUrl, {
     connectTimeout: 5000,    
     reconnectPeriod: 3000 
});


//client events
client.on("connect", () => {

    console.log("connected to mqtt broker");
    client.subscribe(topicSender, (err) => {
        if (err) 
        {
            console.log("error to subscribe in topic: " + topicSender);
        }
    });

    client.publish(topicReceiver, "state");
    connectToEsp();
});

//recive message
client.on("message", (topicSender, message) => {
    const msg = message.toString().toLowerCase();
    if (msg === "alarm_on") {
        toggle.checked = true;
        statusText.textContent = "Alarme Ligado";
        statusText.style.color = "#5cb85c";
    } else if (msg === "alarm_off") {
        toggle.checked = false;
        statusText.textContent = "Alarme Desligado";
        statusText.style.color = "#d9534f";
    }

});

//connection errors
client.on("error", (err) => {
    toggle.disabled = true;
    showErrorMqttModal("O servidor MQTT não está disponível.");
});

client.on("offline", (err) => {
    toggle.disabled = true;
    showErrorMqttModal("Servidor indisponível.");
});



//send message
toggle.addEventListener("change", () => {

    const oldState = !toggle.checked;
    toggle.checked = oldState;

    // save event
    let event = oldState ? "off" : "on";

    fetch('../../services/logs/logs_event.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded' 
        },
        body: new URLSearchParams({ event: event })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            console.log("Evento registrado com sucesso");
        } else {
            console.error("Erro ao registrar evento", data);
        }
    })
    .catch(err => console.error("Erro na requisição", err));

    client.publish(topicReceiver,"PULSE/1000");
});


// get first state

function requestEspState(timeout = 4000) { // timeout em ms
    return new Promise((resolve, reject) => {
        let answered = false;
        const handleMessage = (topicSender, message) => {
            const msg = message.toString().toLowerCase();
            if (msg === "true" || msg === "false") {
                answered = true;
                client.off("message", handleMessage); 
                resolve(msg); 
            }
        };

        client.on("message", handleMessage);

     
        client.publish(topicReceiver, "state");

    
        setTimeout(() => {
            if (!answered) {
                client.off("message", handleMessage);
                reject(new Error("Não foi possível conectar ao ESP"));
            }
        }, timeout);
    });
}

function connectToEsp()
{
    requestEspState(3000)
    .then(state => {
        toggle.checked = state === "true";
        toggle.disabled = false;
        statusText.textContent = toggle.checked ? "Alarme Ligado" : "Alarme Desligado";
        statusText.style.color = toggle.checked ? "#5cb85c" : "#d9534f";
    })
    .catch(err => {
        toggle.disabled = true;
        showErrorMqttModal("Não foi possivel estabelecer conexão com o esp");
    })
}





// UI //

function showErrorMqttModal(message)
{
    errorMqttText.textContent = message;
    errorMqtt.style.display = "flex";
}

function hideErrorMqtt () 
{
    errorMqtt.style.display = "none";
}

errorMqttClose.addEventListener("click", hideErrorMqtt);

errorMqttReconnectBtn.addEventListener("click", () => {

    hideErrorMqtt();
    statusText.textContent = "Tentando reconectar...";
    statusText.style.color = "#FFFFFFFF";

    if(errorMqttText.textContent.includes("esp"))
    {
        connectToEsp();
    }

    else
    {
        client.reconnect();
    }
    
});