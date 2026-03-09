<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JSON to Form</title>
</head>

<body>
    <form id="jsonToForm" method="post" action="{{ route('handleWebhook') }}">
        @csrf
        <label for="reference">Reference:</label>
        <input type="text" name="reference" id="reference" required><br>

        <label for="session_id">Session ID:</label>
        <input type="text" name="session_id" id="session_id" required><br>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" required><br>

        <label for="fee">Fee:</label>
        <input type="number" name="fee" id="fee" required><br>

        <label for="account_number">Account Number:</label>
        <input type="text" name="account_number" id="account_number" required><br>

        <label for="originator_account_name">Originator Account Name:</label>
        <input type="text" name="originator_account_name" id="originator_account_name" required><br>

        <label for="originator_account_number">Originator Account Number:</label>
        <input type="text" name="originator_account_number" id="originator_account_number" required><br>

        <label for="originator_bank">Originator Bank:</label>
        <input type="text" name="originator_bank" id="originator_bank" required><br>

        <label for="timestamp">Timestamp:</label>
        <input type="text" name="timestamp" id="timestamp" required><br>

        <button type="submit">Submit</button>
    </form>

    <script>
        // Your JSON data
        var jsonData = {
            "reference": "Above-E-Business-Hub-32e8341c-f4e5-4b6d-aaca-d7108785cf8c",
            "session_id": "090267240201141154680013893246",
            "amount": 100,
            "fee": 45,
            "account_number": "4602704073",
            "originator_account_number": "",
            "originator_account_name": "NWACHUKWU ANTHONY UCHECHUKWU",
            "originator_bank": "",
            "timestamp": "2024-02-01T13:12:28.307Z"
        };

        // Function to populate form with JSON data
        function populateForm(jsonData) {
            for (var key in jsonData) {
                if (jsonData.hasOwnProperty(key)) {
                    var inputField = document.getElementById(key);
                    if (inputField) {
                        inputField.value = jsonData[key];
                    }
                }
            }
        }

        // Populate the form with JSON data
        populateForm(jsonData);
    </script>

</body>

</html>
