<?php
session_start(); // Start the session (Make sure this is the first line of your PHP code)

// Function to generate a random dice value between 1 and 3
function rollDice()
{
    return rand(1, 3);
}

function display()
{
    echo "A position: " . $_SESSION['diceA'] . "\n";
    echo "B position: " . $_SESSION['diceB'] . "\n";
    echo "\n";
}

// Initialize the session variables only once
if (!isset($_SESSION['diceA'])) {
    $_SESSION['diceA'] = 0;
}

if (!isset($_SESSION['diceB'])) {
    $_SESSION['diceB'] = 0;
}

// Set the default step to A if it's not defined
if (!isset($_SESSION['step'])) {
    $_SESSION['step'] = 'A';
}

// Perform the game logic here
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['step'] === 'A') {
        $diceAValue = rand(1,3);
        $_SESSION['diceA'] += $diceAValue;

        if ($_SESSION['diceA'] <= 8) {
            if ($_SESSION['diceA'] === 8) {
                session_destroy(); 
                header("Location: awon.php"); // Redirect to the same page
            }
            if (isAvailablea()) {
                $_SESSION['diceB'] = 0; 
            }
        }

        if ($_SESSION['diceA'] > 8) {
            $_SESSION['diceA'] -= $diceAValue;
        }
        $_SESSION['step'] = 'B';

    } elseif ($_SESSION['step'] === 'B') {
        $diceBValue = rand(1,3);
        $_SESSION['diceB'] += $diceBValue;
        if ($_SESSION['diceB'] <= 8) {
            if ($_SESSION['diceB'] === 8) {
                session_destroy(); 
                header("Location: bwon.php"); 
            }
            if (isAvailableb()) {
                $_SESSION['diceA'] = 0; 
            }
        }

        if ($_SESSION['diceB'] > 8) {
            $_SESSION['diceB'] -= $diceBValue;
            
        }
        $_SESSION['step'] = 'A';
    }

    // display();
}

function isAvailablea()
{
    if ($_SESSION['diceA'] == 0 || $_SESSION['diceA'] == 4) {
        return false;
    } else if ($_SESSION['diceA'] == $_SESSION['diceB']) {
        return true;
    } else {
        return false;
    }
}

function isAvailableb()
{
    if ($_SESSION['diceB'] == 0 || $_SESSION['diceB'] == 4) {
        return false;
    } else if ($_SESSION['diceB'] == $_SESSION['diceA']) {
        return true;
    } else {
        return false;
    }
}
if (isset($_POST['stop'])) {
    session_destroy(); // Destroy the session and reset all session variables to zero
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Ludo Game</title>
    
    <style>
        body {
            background:url("images/bg.webp");
            background-position: 100% 100%; 
            background-repeat: no-repeat; 
            background-size: cover;
            opacity:0.9 ;
            
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #b34532;
            margin-top:0px;
            margin-bottom:20px;
        }

        form {
            margin: 20px auto;
            max-width: 250px;
            opacity:0.9 ;
            padding-top:9px;
            height:115px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .stop-form
        {
            opacity:0.9 ;
            margin-top:-10px;
            width:10%;
            padding-top:9px;
            height:55px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="number"] {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            /* width: 100%; */
        }

        input[type="submit"] {
            display: block;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: auto;
            margin-right: auto;
            margin-top: 6px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .dice-button {
            width: 50px;
            height: 50px;
            background-color: red; 
            border: 1px solid #333;
            border-radius: 8px;
            position: relative;
            cursor: pointer;
            font-size: 16px;
            color: #fff; 
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left:auto;
            margin-right:auto;
            overflow: hidden;
            transition: background-color 0.3s ease;
        }

        .dice-dot {
            width: 10px;
            height: 10px;
            background-color: white; 
            border-radius: 50%;
            position: absolute;
        }

        .dice-dot:nth-child(1) {
            top: 5px;
            left: 5px;
        }

        .dice-dot:nth-child(2) {
            top: 5px;
            right: 5px;
        }

        .dice-dot:nth-child(3) {
            bottom: 5px;
            left: 5px;
        }

        .dice-dot:nth-child(4) {
            bottom: 5px;
            right: 5px;
        }

        .dice-dot:nth-child(5) {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .dice-dot:nth-child(6) {
            top: 25px;
            left: 50%;
            transform: translateX(-50%);
        }

        .dice-dot:nth-child(7) {
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
        }
        @keyframes roll {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .dice-roll {
            animation: roll 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        .board-container {
        display: flex;
        justify-content: center;
        align-items: center;
        }
        .board {
        display: grid;
        grid-template-columns: repeat(3, 100px);
        grid-template-rows: repeat(3, 100px);

        }
        .cell {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        position: relative;
        text-align: center;
        }

        .cell-content {
                font-size: 24px; 
            }

        .start-text {
            font-size: 12px; 
            position: absolute;
            top: 13%;
        
        }
    </style>
</head>
<body>
    <h1>Mini Ludo Game</h1>
    <?php if ($_SESSION['step'] === 'A'): ?>

    <div class="board-container">
    <div class="board">
        <?php
        $diceAValue = $_SESSION['diceA'];
        $diceBValue = $_SESSION['diceB'];

        // Helper function to generate the content for a cell based on its value
        function getCellContent($cellValue, $diceAValue, $diceBValue) {
            $content = "";
            if ($cellValue == $diceAValue && $cellValue == $diceBValue) {
                $content = "AB";
            } elseif ($cellValue == $diceAValue) {
                $content = "A";
            } elseif ($cellValue == $diceBValue) {
                $content = "B";
            }
            return $content;
        }
        ?>

        <div class="cell" value="7">
            <span class="cell-content"><?php echo getCellContent(7, $diceAValue, $diceBValue); ?></span>
            <div class="start-text"></div>
        </div>
        <div class="cell" value="6">
            <span class="cell-content"><?php echo getCellContent(6, $diceAValue, $diceBValue); ?></span>
            <div class="start-text"></div>
        </div>
        <div class="cell" value="5">
            <span class="cell-content"><?php echo getCellContent(5, $diceAValue, $diceBValue); ?></span>
            <div class="start-text"></div>
        </div>
        <div class="cell" value="0">
            <span class="cell-content"><?php echo getCellContent(0, $diceAValue, $diceBValue); ?></span>
            <div class="start-text">Start</div>
        </div>
        <div class="cell" value="8">
            <span class="cell-content"><?php echo getCellContent(8, $diceAValue, $diceBValue); ?></span>
            <div class="start-text">Home</div>
        </div>
        <div class="cell" value="4">
            <span class="cell-content"><?php echo getCellContent(4, $diceAValue, $diceBValue); ?></span>
            <div class="start-text">Safe</div>
        </div>
        <div class="cell" value="1">
            <span class="cell-content"><?php echo getCellContent(1, $diceAValue, $diceBValue); ?></span>
            <div class="start-text"></div>
        </div>
        <div class="cell" value="2">
            <span class="cell-content"><?php echo getCellContent(2, $diceAValue, $diceBValue); ?></span>
            <div class="start-text"></div>
        </div>
        <div class="cell" value="3">
            <span class="cell-content"><?php echo getCellContent(3, $diceAValue, $diceBValue); ?></span>
            <div class="start-text"></div>
        </div>
    </div>
</div>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="diceA">A's TURN</label>
            <input type="hidden" name="step" value="B">
            <br>
            <button type="submit" class="dice-button" title="Roll Dice" onclick="rollDice()">
                <span class="dice-dot"></span>
                <span class="dice-dot"></span>
                <span class="dice-dot"></span>
                <span class="dice-dot"></span>
                <span class="dice-dot"></span>
            </button>
            
        </form>
    <?php elseif ($_SESSION['step'] === 'B'): ?>
    <div class="board-container">
    <div class="board">
        <?php
        $diceAValue = $_SESSION['diceA'];
        $diceBValue = $_SESSION['diceB'];

        // Helper function to generate the content for a cell based on its value
        function getCellContent($cellValue, $diceAValue, $diceBValue) {
            $content = "";
            if ($cellValue == $diceAValue && $cellValue == $diceBValue) {
                $content = "AB";
            } elseif ($cellValue == $diceAValue) {
                $content = "A";
            } elseif ($cellValue == $diceBValue) {
                $content = "B";
            }
            return $content;
        }
        ?>

<div class="cell" value="7">
            <span class="cell-content"><?php echo getCellContent(7, $diceAValue, $diceBValue); ?></span>
            <div class="start-text"></div>
        </div>
        <div class="cell" value="6">
            <span class="cell-content"><?php echo getCellContent(6, $diceAValue, $diceBValue); ?></span>
            <div class="start-text"></div>
        </div>
        <div class="cell" value="5">
            <span class="cell-content"><?php echo getCellContent(5, $diceAValue, $diceBValue); ?></span>
            <div class="start-text"></div>
        </div>
        <div class="cell" value="0">
            <span class="cell-content"><?php echo getCellContent(0, $diceAValue, $diceBValue); ?></span>
            <div class="start-text">Start</div>
        </div>
        <div class="cell" value="8">
            <span class="cell-content"><?php echo getCellContent(8, $diceAValue, $diceBValue); ?></span>
            <div class="start-text">Home</div>
        </div>
        <div class="cell" value="4">
            <span class="cell-content"><?php echo getCellContent(4, $diceAValue, $diceBValue); ?></span>
            <div class="start-text">Safe</div>
        </div>
        <div class="cell" value="1">
            <span class="cell-content"><?php echo getCellContent(1, $diceAValue, $diceBValue); ?></span>
            <div class="start-text"></div>
        </div>
        <div class="cell" value="2">
            <span class="cell-content"><?php echo getCellContent(2, $diceAValue, $diceBValue); ?></span>
            <div class="start-text"></div>
        </div>
        <div class="cell" value="3">
            <span class="cell-content"><?php echo getCellContent(3, $diceAValue, $diceBValue); ?></span>
            <div class="start-text"></div>
        </div>
    </div>
</div>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="diceB">B's TURN</label>
            <input type="hidden" name="step" value="A">
            <br>
            <button type="submit" class="dice-button" title="Roll Dice" onclick="rollDice()">
                <span class="dice-dot"></span>
                <span class="dice-dot"></span>
                <span class="dice-dot"></span>
                <span class="dice-dot"></span>
                <span class="dice-dot"></span>
            </button>
        </form>
    <?php endif; ?>
    <form class="stop-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="stop" value="1">
        <input type="submit" value="Stop Game">
    </form>
    
</body>
<script>
    function rollDice() {
    let diceButton = document.querySelector('.dice-button');
    diceButton.classList.add('dice-roll');
}
</script>
</html>

