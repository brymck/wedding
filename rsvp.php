        <h1>RSVP</h1>
        <?php
        function display_value($key, $default = "") {
          if (isset($_SESSION[$key])) {
            return ' value="' . $_SESSION[$key] . '"';
          } elseif ($default !== "") {
            return ' value="' . $default . '"';
          }
        }

        function show_checked($key, $default = 0) {
          if (isset($_SESSION[$key])) {
            if ($_SESSION[$key] === "yes") {
              return " checked";
            }
          } elseif ($default === 1) {
            return "checked";
          }
        }

        if (isset($_SESSION["errors"])):
          $errors = $_SESSION["errors"];
        ?>
        <div class="notice">
          <p>Yo! You need to fill stuff out first!</p>
          <ul>
            <?php if (in_array("no_name", $errors)): ?>
            <li>No name</li>
            <?php endif; ?>
            <?php if (in_array("no_email", $errors)): ?>
            <li>No email provided</li>
            <?php endif; ?>
            <?php if (in_array("bad_email", $errors)): ?>
            <li>Email doesn't seem valid</li>
            <?php endif; ?>
            <?php if (in_array("no_one", $errors)): ?>
            <li>No one's attending?</li>
            <?php endif; ?>
            <?php if (in_array("no_event", $errors)): ?>
            <li>Which event(s) are you attending?</li>
            <?php endif; ?>
          </ul>
        </div>
        <?php endif; ?>
        <form name="attend" method="post" action="submit">
          <ol>
            <li>
              <label for="name">Name</label>
              <input type="text" name="name" id="name"<?php echo display_value("name"); ?> placeholder="John Smith" required autofocus>
            </li>
            <li>
              <label for="email">Email</label>
              <input type="email" name="email" id="email"<?php echo display_value("email"); ?> placeholder="john.smith@gmail.com" required>
            </li>
            <li>
              <label for="attending">Attending?</label>
              <input type="checkbox" name="attending" id="attending"<?php echo show_checked("attending", 1); ?> onclick="checkAttendance()">
            </li>
            <li>
              <label for="number">Number attending</label>
              <input type="number" name="number" id="number"<?php echo display_value("number", 1); ?> min="1" step="1" required>
            </li>
            <li>
              <label for="wedding">Wedding</label>
              <input type="checkbox" name="wedding" id="wedding"<?php echo show_checked("wedding", 1); ?>>
            </li>
            <li>
              <label for="friday">Friday night party</label>
              <input type="checkbox" name="friday" id="friday"<?php echo show_checked("friday", 1); ?>>
            </li>
            <li>
              <label for="notes">Notes</label>
              <textarea name="notes" id="notes" rows="8" cols="60" placeholder="Leave us any comments here!"><?php echo $_SESSION["notes"]; ?></textarea>
            </li>
          </ol>
          <input type="submit" name="submit" id="submit" value="Submit" />
        </form>
        <?php session_destroy(); ?>
