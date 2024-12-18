<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Conversationnel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #chat-container {
            max-height: 500px;
            overflow-y: auto;
        }

        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
        }

        .sent {
            background-color: #d1e7dd;
            text-align: right;
        }

        .received {
            background-color: #2d3bb970;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center">Chat Conversationnel</h1>
        <div id="chat-container" class="border p-3 mb-3">
            <!-- Messages seront ajoutés dynamiquement ici -->
        </div>
        <form id="chat-form">
            <div class="input-group">
                <input type="text" id="user-message" class="form-control" placeholder="Entrez votre question..."
                    required>
                <button class="btn btn-primary" type="submit">Envoyer</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const chatContainer = document.getElementById("chat-container");
            const chatForm = document.getElementById("chat-form");
            const userMessageInput = document.getElementById("user-message");

            // Ajouter un message dans le chat
            function addMessage(content, type) {
                const messageDiv = document.createElement("div");
                messageDiv.classList.add("message", type === "sent" ? "sent" : "received");
                messageDiv.innerHTML = `<strong>${type === "sent" ? "Vous" : "Gemini"}:</strong> ${content}`;
                chatContainer.appendChild(messageDiv);
                chatContainer.scrollTop = chatContainer.scrollHeight; // Scroll vers le bas
            }

            // Gestion de l'envoi du formulaire
            chatForm.addEventListener("submit", async (e) => {
                e.preventDefault(); // Empêcher le rechargement de la page

                const userMessage = userMessageInput.value.trim();
                if (!userMessage) return;

                // Ajouter le message de l'utilisateur
                addMessage(userMessage, "sent");

                // Réinitialiser le champ d'entrée
                userMessageInput.value = "";

                try {
                    const apiKey = "AIzaSyD2BmJyL5tvDH61GrUUYTgQXGlSahSwUpQ"; // Remplacez par votre clé API réelle
                    const apiUrl =
                        `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=${apiKey}`;

                    const requestBody = {
                        contents: [{
                            parts: [{
                                text: userMessage,
                            }, ],
                        }, ],
                    };

                    const response = await fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(requestBody),
                    });

                    if (!response.ok) {
                        throw new Error("Erreur lors de la communication avec l'API Gemini.");
                    }

                    const data = await response.json();

                    // Extraire la réponse du premier candidat
                    const reply =
                        data.candidates && data.candidates[0] && data.candidates[0].content.parts[0]
                        .text ?
                        data.candidates[0].content.parts[0].text :
                        "Pas de réponse.";

                    // Ajouter la réponse de l'API dans le chat
                    addMessage(reply, "received");
                } catch (error) {
                    console.error("Erreur:", error);
                    addMessage("Une erreur est survenue. Veuillez réessayer.", "received");
                }
            });
        });
    </script>
</body>

</html>
