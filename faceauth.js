document.addEventListener("DOMContentLoaded", () => {
    const video = document.querySelector("#video");
    const canvas = document.querySelector("#canvas");
    const captureButton = document.querySelector("#captureButton");
    const retryButton = document.querySelector("#retryButton");
    const submitButton = document.querySelector("#submitButton");
    const imagePreview = document.querySelector("#imagePreview");
    const loader = document.querySelector("#loader");
    const messageBox = document.querySelector("#messageBox");
    const csrfToken = document.querySelector("input[name='sesskey']").value;

    // Initialize webcam feed
    function startCamera() {
        navigator.mediaDevices
            .getUserMedia({ video: true })
            .then((stream) => {
                video.srcObject = stream;
                video.play();
            })
            .catch((error) => {
                console.error("Error accessing webcam: ", error);
                displayMessage("Error accessing webcam. Please try again.");
            });
    }

    // Capture image from video feed
    captureButton.addEventListener("click", () => {
        const context = canvas.getContext("2d");

        // Set canvas size to match video dimensions
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        // Draw the current video frame to the canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = canvas.toDataURL("image/jpeg");

        // Switch to image preview mode
        toggleVideoMode(false);
        imagePreview.src = imageData;
        imagePreview.style.display = "block";
    });

    // Retry capturing the image
    retryButton.addEventListener("click", () => {
        toggleVideoMode(true);
    });

    // Submit the captured image to the server
    submitButton.addEventListener("click", () => {
        const imageData = canvas.toDataURL("image/jpeg");

        // Show loader while submitting
        loader.style.display = "block";

        fetch("enrollment.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": csrfToken,
            },
            body: JSON.stringify({ image: imageData }),
        })
            .then((response) => response.json())
            .then((data) => {
                loader.style.display = "none";

                if (data.success) {
                    displayMessage("Face enrolled successfully!", "success");
                } else {
                    displayMessage(data.error || "Failed to enroll face.", "error");
                }
            })
            .catch((error) => {
                console.error("Error submitting face data: ", error);
                loader.style.display = "none";
                displayMessage("An error occurred. Please try again.", "error");
            });
    });

    // Display messages to the user
    function displayMessage(message, type = "info") {
        messageBox.textContent = message;
        messageBox.className = `message ${type}`;
        messageBox.style.display = "block";

        setTimeout(() => {
            messageBox.style.display = "none";
        }, 5000);
    }

    // Toggle between video and image modes
    function toggleVideoMode(isVideoMode) {
        video.style.display = isVideoMode ? "block" : "none";
        captureButton.style.display = isVideoMode ? "inline-block" : "none";
        retryButton.style.display = isVideoMode ? "none" : "inline-block";
        submitButton.style.display = isVideoMode ? "none" : "inline-block";
        imagePreview.style.display = isVideoMode ? "none" : "block";
    }

    // Start the webcam feed on page load
    startCamera();
});
