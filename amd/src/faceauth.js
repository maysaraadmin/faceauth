define(['jquery'], function($) {
    return {
        init: async function() {
            $(document).ready(async function() {
                const video = document.querySelector("#video");
                const canvas = document.querySelector("#canvas");
                const captureButton = document.querySelector("#captureButton");
                const retryButton = document.querySelector("#retryButton");
                const submitButton = document.querySelector("#submitButton");
                const imagePreview = document.querySelector("#imagePreview");
                const loader = document.querySelector("#loader");
                const messageBox = document.querySelector("#messageBox");
                const csrfToken = document.querySelector("input[name='sesskey']").value;

                function startCamera() {
                    navigator.mediaDevices.getUserMedia({ video: true })
                        .then((stream) => {
                            video.srcObject = stream;
                            video.play();
                        })
                        .catch((error) => {
                            console.error("Error accessing webcam: ", error);
                            displayMessage(M.util.get_string('erroraccessingwebcam', 'auth_faceauth'), "error");
                        });
                }

                captureButton.addEventListener("click", () => {
                    const context = canvas.getContext("2d");
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const imageData = canvas.toDataURL("image/jpeg");
                    toggleVideoMode(false);
                    imagePreview.src = imageData;
                    imagePreview.style.display = "block";
                });

                retryButton.addEventListener("click", () => {
                    toggleVideoMode(true);
                });

                submitButton.addEventListener("click", () => {
                    const imageData = canvas.toDataURL("image/jpeg");
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
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        return response.json();
                    })
                    .then((data) => {
                        loader.style.display = "none";
                        if (data.success) {
                            displayMessage(M.util.get_string('faceenrolledsuccessfully', 'auth_faceauth'), "success");
                        } else {
                            displayMessage(data.error || M.util.get_string('failedtoenrollface', 'auth_faceauth'), "error");
                        }
                    })
                    .catch((error) => {
                        console.error("Error submitting face data: ", error);
                        loader.style.display = "none";
                        displayMessage(M.util.get_string('erroroccurred', 'auth_faceauth'), "error");
                    });
                });

                function displayMessage(message, type = "info") {
                    messageBox.textContent = message;
                    messageBox.className = `message ${type}`;
                    messageBox.style.display = "block";

                    setTimeout(() => {
                        messageBox.style.display = "none";
                    }, 5000);
                }

                function toggleVideoMode(isVideoMode) {
                    video.style.display = isVideoMode ? "block" : "none";
                    captureButton.style.display = isVideoMode ? "inline-block" : "none";
                    retryButton.style.display = isVideoMode ? "none" : "inline-block";
                    submitButton.style.display = isVideoMode ? "none" : "inline-block";
                    imagePreview.style.display = isVideoMode ? "none" : "block";
                }

                startCamera();
            });
        }
    };
});