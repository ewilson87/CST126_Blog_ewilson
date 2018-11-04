<?php
/**
 * CST-126 Blog project
 * new_topic.php version 1.0
 * Program Author: Evan Wilson, Branden Manibusan, and Nicholas Thomas
 * Date: 10/24/2018
 * Custom script for custom alert dialogs
 * References: https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database
 * Site was used in initial development of application with many changes implemented.
 */
?>
<!DOCTYPE html>
<html>
<head>
    <script>
        let ALERT_TITLE = "Whoops!";
        let ALERT_BUTTON_TEXT = "Close";

        if(document.getElementById) {
            window.alert = function(txt) {
                createCustomAlert(txt);
            }
        }

        function createCustomAlert(txt) {
            let d, alertObj, modalObj, title, msg, btn;
            d = document;

            if(d.getElementById("modalContainer")) return;

            modalObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
            modalObj.id = "modalContainer";
            modalObj.style.height = d.documentElement.scrollHeight + "px";

            alertObj = modalObj.appendChild(d.createElement("div"));
            alertObj.id = "alertBox";
            if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
            alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
            alertObj.style.visiblity="visible";

            title = alertObj.appendChild(d.createElement("h1"));
            title.appendChild(d.createTextNode(ALERT_TITLE));

            msg = alertObj.appendChild(d.createElement("p"));
            msg.innerHTML = txt;

            btn = alertObj.appendChild(d.createElement("a"));
            btn.id = "closeBtn";
            btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
            btn.href = "#";
            btn.focus();
            btn.onclick = function() { removeCustomAlert();return false; }

            alertObj.style.display = "block";

        }

        function removeCustomAlert() {
            document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
        }
    </script>
</head>
</html>