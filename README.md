### README: Face Authentication Plugin for Moodle

---

#### **Plugin Name**: Face Authentication (faceauth)  
#### **Version**: 1.0.0  
#### **Moodle Compatibility**: Moodle 4.0+  
#### **Author**: Your Name  
#### **License**: [GNU GPL v3](http://www.gnu.org/copyleft/gpl.html)  

---

### **Overview**

The Face Authentication plugin provides a seamless and secure method for Moodle users to authenticate using facial recognition. This plugin integrates modern face recognition technology to enhance the user experience and add a layer of convenience and security to Moodle's login process.

---

### **Features**
- Facial recognition-based login for Moodle users.
- Manual login disabled for face-authenticated users, ensuring exclusive face-authentication usage.
- Compatible with Moodle’s default authentication workflows.
- Easy-to-use interface for capturing and verifying faces during login.
- Customizable settings for API integration and logging.

---

### **Installation**

1. **Download the Plugin**:  
   Clone or download the plugin from the [GitHub repository](https://github.com/maysaraadmin/faceauth).

   ```bash
   git clone https://github.com/maysaraadmin/faceauth.git

   Copy to Moodle Auth Directory:
   
Place the plugin folder into the auth directory of your Moodle installation:
mv auth_faceauth /path/to/moodle/auth/


install the Plugin:

Log in to Moodle as an admin.

Navigate to Site administration > Notifications.

Follow the prompts to complete the installation.

Enable the Plugin:

Go to Site administration > Plugins > Authentication > Manage authentication.

Enable Face Authentication.

Configure the Plugin:

Navigate to Site administration > Plugins > Authentication > Face Authentication.

Add the API URL for face verification, enable logging (optional), and save settings.

Usage
Users attempting to log in will be prompted to authenticate via face capture.

The plugin verifies the face data using your configured API or custom verification logic.

On successful verification, the user is logged into Moodle.

Configuration
Plugin Settings
API URL:
Set the endpoint for face verification API.

Enable Logging:
Enable or disable logging for face authentication activities.

Developer Notes
Core Files:
authlib.php: Implements authentication logic.

login.php: Manages the login interface for face capture and submission.

db/install.xml: Defines database schema for the plugin.

db/upgrade.php: Handles schema updates during plugin upgrades.

Frontend Dependencies:
faceauth.js: Manages client-side operations like face capture and API requests.

style.css: Provides styling for the face authentication interface.

Extensibility:
Replace verify_face_data in authlib.php with your custom API logic or face matching algorithm.

Contributing
Contributions are welcome!
If you have ideas for improvement or encounter issues, please create a pull request or open an issue on the GitHub repository.

License
This plugin is licensed under the GNU General Public License v3. For more details, see the LICENSE file.

Support
For questions or support, please reach out via the GitHub issues page.

Screenshots
Login Interface

Captures user face and displays options for retry or submission.

Admin Settings

Configure API endpoints and enable logging.

Add screenshots to illustrate these features!
