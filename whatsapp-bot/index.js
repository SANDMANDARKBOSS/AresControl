const { default: makeWASocket, useMultiFileAuthState, DisconnectReason } = require('@whiskeysockets/baileys');
const pino = require('pino');
const express = require('express');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.json());

let sock;

async function connectToWhatsApp() {
    const { state, saveCreds } = await useMultiFileAuthState('auth_info_baileys');
    
    sock = makeWASocket({
        auth: state,
        logger: pino({ level: 'silent' }),
        printQRInTerminal: true,
        browser: ['Ares Gym Bot', 'Chrome', '1.0.0']
    });

    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;
        
        if (qr) {
            console.log('🤖 Escanea este código QR con el WhatsApp de Ares Gym:');
        }

        if (connection === 'close') {
            const shouldReconnect = (lastDisconnect.error)?.output?.statusCode !== DisconnectReason.loggedOut;
            console.log('connection closed due to ', lastDisconnect.error, ', reconnecting ', shouldReconnect);
            
            if (shouldReconnect) {
                connectToWhatsApp();
            }
        } else if (connection === 'open') {
            console.log('✅ WhatsApp conectado exitosamente!');
        }
    });

    sock.ev.on('creds.update', saveCreds);
}

connectToWhatsApp();

// API Endpoint to send messages
app.post('/send-message', async (req, res) => {
    try {
        const { number, message } = req.body;

        if (!number || !message) {
            return res.status(400).json({ error: 'Faltan los parámetros number o message' });
        }

        // Eliminar +, espacios, y caracteres especiales. Asumimos código país 593 (Ecuador)
        let cleanNumber = number.replace(/\D/g, '');
        if (cleanNumber.startsWith('0')) {
            cleanNumber = '593' + cleanNumber.substring(1);
        }

        const jid = `${cleanNumber}@s.whatsapp.net`;

        if (sock) {
            await sock.sendMessage(jid, { text: message });
            return res.status(200).json({ success: true, message: 'Mensaje enviado correctamente a ' + jid });
        } else {
            return res.status(500).json({ error: 'WhatsApp no está conectado todavía' });
        }
    } catch (error) {
        console.error('Error enviando mensaje:', error);
        return res.status(500).json({ error: 'Fallo al enviar el mensaje' });
    }
});

const PORT = 3000;
app.listen(PORT, () => {
    console.log(`🚀 Ares Gym WhatsApp Bot corriendo en http://localhost:${PORT}`);
});
