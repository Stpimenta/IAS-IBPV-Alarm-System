<?php

session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
  header('Location: ../login/index.php');
  exit;
}

if (!isset($_SESSION['jwtToken'])) {
  header('Location: ../login/index.php');
  exit;
}


$tokenParts = explode('.', $_SESSION['jwtToken']);
if (count($tokenParts) !== 3) {
  header('Location: ../login/index.php');
  exit;
}

$payload = json_decode(base64_decode($tokenParts[1]), true);


if (!isset($payload['exp']) || time() > $payload['exp']) {

  session_destroy();
  header('Location: ../login/index.php');
  exit;
}


if (!isset($payload['alarmAuth']) || $payload['alarmAuth'] !== "True") {

  session_destroy();
  header('Location: ../login/index.php');
  exit;
}




?>


<!DOCTYPE html>

<html>

<head>
  <meta charset="utf-8">
  <title>Sistema de Alarme</title>
  <link rel="stylesheet" href="./alarm.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

  <header>

    <div id="div-header-logo">
      <img src="../../../assets/images/securityIcon.png" alt="logo-IBPV">
      <h1>IAS</h1>
    </div>

    <div id="div-header-user-icon">
      <img src="../../../assets/images/userIcon2.png" alt="user-button">
      <div id="user-dropdown" class="dropdown-menu">
        <a href="../login/logout.php">Sair</a>
      </div>
    </div>

  </header>


  <main>


    <section id="sec-context">

      <div id="div-context">
        <img src="../../../assets/images/security-management.png" alt="logo-IBPV">
      </div>

    </section>

    <section id="sec-switch">

      <div id="div-switch">
        <label class="switch">
          <input type="checkbox" id="toggle">
          <span class="slider"></span>
        </label>
        <div class="status" id="status">Carregando...</div>
      </div>

    </section>

    <section id="sec-button-history">
      <button id="btn-history">Ver Histórico</button>
    </section>



    <!-- history modal -->
    <div id="modal-history" class="modal">
      <div class="modal-content">
        <span class="modal-close">&times;</span>
        <h2>Últimas Ativações</h2>
        <ul class="history-list">


        </ul>
      </div>
    </div>

    <!-- error modal -->
    <div id="error-mqtt" class="error-mqtt">
      <div class="error-mqtt-content">
        <span id="error-mqtt-close" class="modal-close">&times;</span>
        <h2>Conexão Perdida</h2>
        <p id="error-mqtt-text">O servidor MQTT não está disponível.</p>
        <button id="error-mqtt-reconnect-btn">Reconectar</button>
      </div>
    </div>


  </main>

  <footer>
    <div id="div-footer-info">
      <p>© IBPV Alarm System</p>
      <p>Desenvolvido por Sergio T. Pimenta</p>
      <a href="https://stpimenta.com" target="_blank">stpimenta.com<a>
    </div>
  </footer>

  <!-- mqtt.js -->
  <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
  <script src="./alarm.js"></script>
  <script src="./alarmMqtt.js"></script>

</body>

</html>