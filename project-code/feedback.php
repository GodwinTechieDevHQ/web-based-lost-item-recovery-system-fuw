<?php include 'header.php'; ?>
<!-- feedback.php -->
<div class="form-container">
    <!-- Feedback Form -->
    <h2>Feedback</h2>
    <form action="includes/feedback.inc.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="feedback-type">Feedback Type:</label>
            <select id="feedback-type" name="feedback-type" class="form-control">
                <option value="suggestion">Suggestion</option>
                <option value="bug">Bug Report</option>
                <option value="question">Question</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" class="form-control" style="width: 100%; padding: 10px;"
                required></textarea>
        </div>
        <div class="form-group">
            <label for="images">Upload Image(s):</label>
            <input type="file" id="images" name="images[]" accept="image/*" multiple class="form-control-file">
            <small class="form-text text-muted">You can upload one or more images (optional).</small>
        </div>
        <button type="submit" class="btn btn-primary">Submit Feedback</button>
    </form>
</div>

<?php include 'footer.php'; ?>