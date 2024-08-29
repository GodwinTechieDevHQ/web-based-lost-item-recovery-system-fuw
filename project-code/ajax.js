// JavaScript for auto-reloading messages using AJAX
$(document).ready(function() {
    // Function to fetch and display messages
    function fetchAndDisplayMessages() {
        // AJAX request to fetch new messages
        $.ajax({
            url: 'fetch_messages.php', // Replace with your server-side script to fetch messages
            type: 'GET',
            data: {
                user_id: <?php echo $user_id; ?>,
                other_user_id: <?php echo $other_user_id; ?>
            },
            dataType: 'json',
            success: function(response) {
                // Append new messages to the chat container
                $('.chat-messages').html(response.messages);

                // Scroll to the bottom of the chat container
                $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
            },
            error: function(error) {
                console.error('Error fetching messages:', error);
            }
        });
    }

    // Fetch and display messages every 5 seconds (adjust as needed)
    setInterval(fetchAndDisplayMessages, 5000);

    // JavaScript for sending messages using AJAX
    function sendMessage(event) {
        // Prevent the default form submission
        event.preventDefault();

        // Get the message text from the textarea
        var messageText = $('#message-textarea').val();

        // AJAX request to send the message
        $.ajax({
            url: 'private_chat.php?other_user_id=<?php echo $other_user_id; ?>', // Updated URL
            type: 'POST',
            data: {
                message_text: messageText
            },
            dataType: 'json',
            success: function(response) {
                // Check the status of the response
                if (response.status === 'success') {
                    // Message sent successfully
                    // Append the new message to the chat container
                    $('.chat-messages').append(response.newMessage);

                    // Clear the message textarea
                    $('#message-textarea').val('');

                    // Scroll to the bottom of the chat container
                    $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
                } else {
                    // Error sending message
                    // Optionally, you can display an error message to the user
                    console.error(response.message);
                }
            },
            error: function(error) {
                console.error('Error sending message:', error);
            }
        });
    }

    // Event listener for the form submission
    $('form').on('submit', function(event) {
        sendMessage(event);
    });

    // Additional code for handling the enter key in the textarea (optional)
    $('#message-textarea').on('keydown', function(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage(event);
        }
    });
});


