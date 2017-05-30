<html>
      <head>
            <title>Comeneat Email Template</title>
      </head>
      <body>
            <div style="width:680px; margin:0 auto;border:1px solid #09a925;border-top:0;">
                  <header style="width:100%; display:inline-block;">
                        <div style="background:#fff; width:calc(100% - 20px); display:inline-block; text-align:center; padding:10px;">
                              <img src="<?php echo $source; ?>"  "alt="{title}"   "title="{title}" width="100" height="100"/>
                        </div>
                  </header>
                  <section style="width:100%; display:inline-block;">
                        <?php echo $mailContent; ?>
                  </section>
                  <footer style="width:100%; display:inline-block;">
                  </footer>
            </div>
      </body>
</html>