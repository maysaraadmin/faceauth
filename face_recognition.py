import sys
import os
import logging
from deepface import DeepFace
import cv2

# Configure logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

def main():
    if len(sys.argv) < 2:
        logging.error("Username not provided.")
        return

    username = sys.argv[1]

    # Path to the user's stored image.
    stored_image_path = f'/path/to/faces/{username}.jpg'

    if not os.path.exists(stored_image_path):
        logging.info("NO_MATCH")
        return

    # Path to store the captured live image.
    live_image_path = '/path/to/temp/live_image.jpg'

    # Capture a live image and handle possible issues.
    if not capture_live_image(live_image_path):
        logging.error("Failed to capture live image.")
        return

    try:
        # Perform facial verification using DeepFace.
        result = DeepFace.verify(img1_path=live_image_path, img2_path=stored_image_path, enforce_detection=True)
        if result.get('verified', False):
            logging.info("MATCH")
        else:
            logging.info("NO_MATCH")
    except Exception as e:
        logging.error("An error occurred during verification.")
        logging.error(e)


def capture_live_image(output_path):
    """
    Captures a live image from the webcam and saves it to the specified path.
    """
    cap = cv2.VideoCapture(0)
    if not cap.isOpened():
        logging.error("Unable to access the camera.")
        return False

    ret, frame = cap.read()
    if not ret:
        logging.error("Failed to capture image from the camera.")
        cap.release()
        return False

    # Save the captured frame as an image.
    try:
        cv2.imwrite(output_path, frame)
    except Exception as e:
        logging.error(f"Unable to save the captured image. {e}")
        cap.release()
        return False

    cap.release()
    return True


if __name__ == "__main__":
    main()