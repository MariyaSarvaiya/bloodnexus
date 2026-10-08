/* =====================================================
   🤖 AI BLOOD BANK CHATBOX
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    const chatButton = document.getElementById("aiChatbotButton");
    const chatBox = document.getElementById("aiChatbotBox");
    const closeButton = document.getElementById("aiChatClose");
    const sendButton = document.getElementById("aiChatSend");
    const input = document.getElementById("aiChatInput");
    const messages = document.getElementById("aiChatMessages");

    // If chatbot HTML is not present, do nothing
    if (!chatButton || !chatBox) {
        return;
    }


    /* =========================
       OPEN CHATBOX
    ========================= */

    chatButton.addEventListener("click", function () {

        chatBox.style.display = "flex";

        chatButton.style.display = "none";

        if (input) {
            input.focus();
        }
    });


    /* =========================
       CLOSE CHATBOX
    ========================= */

    if (closeButton) {

        closeButton.addEventListener("click", function () {

            chatBox.style.display = "none";

            chatButton.style.display = "flex";

        });

    }


    /* =========================
       ADD MESSAGE
    ========================= */

    function addMessage(text, type) {

        const message = document.createElement("div");

        message.className = "ai-message " + type;

        message.innerHTML = text;

        messages.appendChild(message);

        messages.scrollTop = messages.scrollHeight;
    }


    /* =========================
       BOT RESPONSE
    ========================= */

    function getBotResponse(message) {

        const text = message.toLowerCase().trim();


        /* =========================
           BLOOD SEARCH
        ========================= */

        if (
            text.includes("find blood") ||
            text.includes("blood search") ||
            text.includes("find donor") ||
            text.includes("available donor")
        ) {

            return `
                🩸 <strong>Find Blood Donor</strong><br><br>

                You can search available donors by
                <strong>blood group</strong> and
                <strong>city</strong> from the
                <strong>Find Blood</strong> section.

                <br><br>

                If a donor is available, you can open
                the donor details and send a blood request.
            `;
        }


        /* =========================
           BLOOD REQUEST
        ========================= */

        if (
            text.includes("request blood") ||
            text.includes("blood request") ||
            text.includes("need blood")
        ) {

            return `
                🚨 <strong>Blood Request</strong><br><br>

                If you need blood, open the
                <strong>Request Blood</strong> section
                and submit the patient's details.

                <br><br>

                You can provide:

                <br>👤 Patient Name
                <br>🩸 Blood Group
                <br>🏥 Hospital
                <br>📍 City
                <br>📞 Contact Number
                <br>⚠️ Urgency
                <br>📝 Reason

                <br><br>

                Your request can then be matched
                with an available donor.
            `;
        }


        /* =========================
           EMERGENCY / URGENT
        ========================= */

        if (
            text.includes("emergency") ||
            text.includes("urgent") ||
            text.includes("critical")
        ) {

            return `
                🚨 <strong>Urgent Blood Request</strong><br><br>

                For an urgent or critical blood requirement,
                use the existing
                <strong>Request Blood</strong> section.

                <br><br>

                While submitting the request, select the
                appropriate <strong>Urgency</strong> level:

                <br>🟢 Normal
                <br>🟠 Urgent
                <br>🔴 Critical

                <br><br>

                Make sure the hospital, city and contact
                information are correct.
            `;
        }


        /* =========================
           DONOR
        ========================= */

        if (
            text.includes("donate") ||
            text.includes("become donor") ||
            text.includes("donor registration")
        ) {

            return `
                ❤️ <strong>Become a Blood Donor</strong><br><br>

                Open the
                <strong>Become a Donor</strong> section
                and register your blood group and
                contact information.

                <br><br>

                Once your donor profile is available,
                other users can find you when searching
                for blood.
            `;
        }


        /* =========================
           ACCEPT REQUEST
        ========================= */

        if (
            text.includes("accept request") ||
            text.includes("accept donor request") ||
            text.includes("donor request")
        ) {

            return `
                📩 <strong>Donor Requests</strong><br><br>

                If you are a registered donor, open
                <strong>Donor Requests</strong> to see
                blood requests sent specifically to you.

                <br><br>

                You can:

                <br>✅ Accept the request
                <br>❌ Reject the request
                <br>✔️ Mark the donation as completed

                <br><br>

                After accepting a request, your donor
                availability changes until the donation
                is completed.
            `;
        }


        /* =========================
           COMPLETED DONATION
        ========================= */

        if (
            text.includes("complete donation") ||
            text.includes("donation completed") ||
            text.includes("mark completed")
        ) {

            return `
                ✔️ <strong>Complete Donation</strong><br><br>

                After the blood donation is completed,
                the donor can use
                <strong>Mark Donation Completed</strong>.

                <br><br>

                The donor will become
                <strong>available again</strong> in the system.
            `;
        }


        /* =========================
           BLOOD GROUPS
        ========================= */

        if (
            text.includes("blood group") ||
            text.includes("a+") ||
            text.includes("a-") ||
            text.includes("b+") ||
            text.includes("b-") ||
            text.includes("ab+") ||
            text.includes("ab-") ||
            text.includes("o+") ||
            text.includes("o-")
        ) {

            return `
                🩸 <strong>Blood Groups</strong><br><br>

                AI Blood Bank supports:

                <br><br>

                A+ &nbsp; A-
                <br>
                B+ &nbsp; B-
                <br>
                AB+ &nbsp; AB-
                <br>
                O+ &nbsp; O-

                <br><br>

                You can search donors according to
                the required blood group.
            `;
        }


        /* =========================
           LOGIN
        ========================= */

        if (
            text.includes("login") ||
            text.includes("sign in")
        ) {

            return `
                🔐 <strong>Login</strong><br><br>

                Use your registered email and password
                to login to <strong>AI Blood Bank</strong>.

                <br><br>

                After login, you can access your
                dashboard and blood bank features.
            `;
        }


        /* =========================
           REGISTRATION
        ========================= */

        if (
            text.includes("register") ||
            text.includes("create account") ||
            text.includes("signup")
        ) {

            return `
                📝 <strong>Create Account</strong><br><br>

                Click <strong>Register</strong> and enter
                your name, email, phone, blood group
                and password.

                <br><br>

                After registration, you can access
                your AI Blood Bank dashboard.
            `;
        }


        /* =========================
           FORGOT PASSWORD
        ========================= */

        if (
            text.includes("forgot password") ||
            text.includes("forget password") ||
            text.includes("forgot my password")
        ) {

            return `
                🔑 <strong>Forgot Password</strong><br><br>

                If you forgot your password, use the
                <strong>Forgot Password</strong> option
                available on the login page.
            `;
        }


        /* =========================
           CONTACT / HELP
        ========================= */

        if (
            text.includes("contact") ||
            text.includes("help")
        ) {

            return `
                📞 <strong>Need Help?</strong><br><br>

                You can use the Blood Search,
                Donor Registration and Blood Request
                sections to manage blood requirements
                and connect with available donors.
            `;
        }


        /* =========================
           ABOUT AI BLOOD BANK
        ========================= */

        if (
            text.includes("about") ||
            text.includes("ai blood bank")
        ) {

            return `
                🤖🩸 <strong>AI Blood Bank</strong><br><br>

                AI Blood Bank is a smart blood donor
                management platform designed to help
                users find available blood donors and
                manage blood requests efficiently.

                <br><br>

                The system provides:

                <br>🩸 Blood Search
                <br>👤 Donor Registration
                <br>📩 Blood Requests
                <br>🤖 AI Blood Assistant
                <br>👨‍💼 Admin Management
            `;
        }


        /* =========================
           GREETING
        ========================= */

        if (
            text.includes("hello") ||
            text.includes("hi") ||
            text.includes("hey")
        ) {

            return `
                👋 Hello! Welcome to
                <strong>AI Blood Bank</strong>.

                <br><br>

                I'm your
                <strong>AI Blood Assistant</strong>.
                How can I help you today? 🩸
            `;
        }


        /* =========================
           DEFAULT
        ========================= */

        return `
            🤖 I'm the <strong>AI Blood Assistant</strong>.

            <br><br>

            You can ask me about:

            <br>🩸 Finding Blood Donors
            <br>❤️ Donating Blood
            <br>🚨 Blood Requests
            <br>📩 Donor Requests
            <br>✔️ Donation Completion
            <br>🔐 Login / Registration
            <br>🔑 Forgot Password
            <br>🩸 Blood Groups
            <br>👨‍💼 AI Blood Bank
        `;
    }


    /* =========================
       SEND MESSAGE
    ========================= */

    function sendMessage() {

        const text = input.value.trim();

        if (text === "") {
            return;
        }


        // User message
        addMessage(text, "user");

        input.value = "";


        // Typing indicator
        const typing = document.createElement("div");

        typing.className = "ai-message bot";

        typing.id = "aiTyping";

        typing.innerHTML = `
            <span class="ai-typing">
                <span></span>
                <span></span>
                <span></span>
            </span>
        `;

        messages.appendChild(typing);

        messages.scrollTop = messages.scrollHeight;


        // Bot reply
        setTimeout(function () {

            const typingElement =
                document.getElementById("aiTyping");

            if (typingElement) {
                typingElement.remove();
            }

            addMessage(
                getBotResponse(text),
                "bot"
            );

        }, 700);
    }


    /* =========================
       SEND BUTTON
    ========================= */

    if (sendButton) {

        sendButton.addEventListener(
            "click",
            sendMessage
        );

    }


    /* =========================
       ENTER KEY
    ========================= */

    if (input) {

        input.addEventListener(
            "keydown",
            function (event) {

                if (event.key === "Enter") {

                    event.preventDefault();

                    sendMessage();

                }

            }
        );

    }


    /* =========================
       QUICK BUTTONS
    ========================= */

    document.addEventListener(
        "click",
        function (event) {

            if (
                event.target.classList.contains(
                    "ai-quick-btn"
                )
            ) {

                const question =
                    event.target.dataset.question;

                if (question && input) {

                    input.value = question;

                    sendMessage();

                }

            }

        }
    );

});