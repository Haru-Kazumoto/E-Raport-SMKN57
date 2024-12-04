<?php

// configuration
$nfAction = 'adm/index.php?page=editor';
$text="";


//$file = 'rereg-cert.php';

// check if form has been submitted
if (isset($_POST['text']))
{
    // save the text contents
    file_put_contents($file, $_POST['text']);

    // redirect to form again
    header(sprintf('Location: %s', $url));
    printf('<a href="%s">Moved</a>.', htmlspecialchars($url));
    exit();
}

// read the textfile
if (isset($_POST['file']))
$text = file_get_contents($file);

?>
<!-- HTML form -->
<form action="<?=$nfAction?>" method="post">
<input type="file" name=nmfile />
<textarea name="text"><?php echo htmlspecialchars($text) ?></textarea>
<input type="submit" />
<input type="reset" />
</form>
