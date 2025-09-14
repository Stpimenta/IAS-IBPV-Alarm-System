# 🔔 IAS – IBPV Alarm System

O **IAS – IBPV Alarm System** foi criado para suprir limitações de sistemas de alarme comuns, oferecendo **controle em tempo real** e **histórico de atividades centralizado**.  
Este projeto não funciona de forma independente, pois é um cliente desenvolvido especificamente para consumir a **IBPV API** (outro sistema já existente).

---

## 🏗️ Arquitetura do Sistema

- 🌐 **Frontend (PHP)**  
  Interface web para interação com o usuário, consumo da IBPV API e gerenciamento de sessão.

- ⚡ **IBPV API (ASP.NET Core)**  
  API que provê autenticação via **JWT**, base de dados integrada e endpoints para consumo do sistema de alarme.

- 🔌 **MQTT Broker**  
  Responsável pela comunicação entre a aplicação e os dispositivos IoT.

- 🔄 **WebSocket sob MQTT**  
  Permite que o usuário acompanhe eventos do alarme em tempo real.

- 📡 **Dispositivos IoT (ESP em C++)**  
  Executam os comandos físicos do alarme e reportam status para o sistema.

---

## 🛠️ Funcionalidades

- 📡 **Controle do sistema de alarme em tempo real**  
- 📑 **Histórico de atividades de usuários e eventos do alarme**  
- 🔐 **Autenticação JWT integrada à IBPV API**  
- 👤 **Gerenciamento de sessão em PHP**  
- 📊 **Centralização de logs de ativação/desativação**  

---

## 🚀 Tecnologias Utilizadas

- **PHP** → frontend e gerenciamento de sessão.  
- **ASP.NET Core API (IBPV API)** → backend principal, autenticação e banco de dados.  
- **PostgreSQL (DB IBPV)** → persistência de dados.  
- **MQTT** → comunicação leve entre sistema e dispositivos.  
- **WebSocket sob MQTT** → atualização em tempo real no frontend.  
- **C++ (ESP-01)** → firmware dos dispositivos IoT.  

---

## 📦 Observação Importante

Este sistema **não é independente**.  
Ele depende diretamente da **IBPV API** para funcionar (autenticação, banco de dados e endpoints).  
Portanto, não há instruções de instalação isolada.

---

## 🌱 Status do Projeto

- ✅ **Ativo:** em funcionamento com integração à IBPV API.  
- 🔄 **Em evolução:** melhorias contínuas no frontend e integração IoT.  
- 🚀 **Foco:** confiabilidade, simplicidade e rastreabilidade de eventos.  

---

## 👤 Autor

Desenvolvido voluntariamente por **Sergio T. Pimenta**.
