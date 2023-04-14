<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>
    <!--[if mso]>
    <style>
    table {border-collapse:collapse;border-spacing:0;border:none;margin:0;}
    div, td {padding:0;}
    div {margin:0 !important;}
    </style>
    <noscript>
    <xml>
    <o:OfficeDocumentSettings>
    <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
    </xml>
    </noscript>
    <![endif]-->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap');
        table, td, div, h1, p {
            font-family: 'Montserrat', sans-serif;
        }
        @media screen and (max-width: 530px) {
            .col-lge {
                max-width: 100% !important;
            }
        }
        @media screen and (min-width: 531px) {
            .col-sml {
                max-width: 27% !important;
            }
            .col-lge {
                max-width: 73% !important;
            }
        }
    </style>
    </head>
    <body style="margin:0; padding:0; word-spacing:normal; background-color:#939297;">
        <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; background-color:#939297;">
            <table role="presentation" style="width:100%; border:none; border-spacing:0;">
                <tr>
                    <td align="center" style="padding:0;">
                    <!--[if mso]>
                    <table role="presentation" align="center" style="width:600px;">
                    <tr>
                    <td>
                    <![endif]-->
                    <table role="presentation" style="width:94%; max-width:600px; border:none; border-spacing:0; text-align:left; font-family:'Montserrat', sans-serif; font-size:16px; line-height:22px; color:#363636;">
                        <tr>
                            <td style="padding:40px 30px 30px 30px; text-align:center; font-size:24px; font-weight:bold;">
                                <a href="<?= base_url(); ?>" style="text-decoration:none;">
                                  <img src="<?= base_url('assets/img/backend/logo-xl-dark.png'); ?>" width="300" alt="Logo" style="width:300px; max-width:100%; height:auto; border:none; text-decoration:none; color:#ffffff;">
                                </a>
                            </td>
                        </tr>

<?php switch($action): ?>
<?php case 'activation': ?>

						<tr>
						    <td style="padding: 30px 30px 0 30px; background-color:#ffffff;">
						        <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">Activation account</h1>
						        <p style="margin:0;">
						            <p>Ciao <?= esc($firstname) . ' ' . esc($lastname); ?>, sei stato aggiunto con successo a <b>Prototype</b>!</p>

						            <p>Ancora un ultimo passo, clicca sul bottone sottostante per impostare la nuova password.</p>

						            <p>Ti ricordiamo il tuo nome utente, che è <b><?= esc($email); ?></b>.</p>

						            <p>Saluti!<br>
						            Lo Staff di <b>Prototype</b>!</p>
						        </p>
						    </td>
						</tr>

<?php break; ?>
<?php case 'reset': ?>

						<tr>
						    <td style="padding: 30px 30px 0 30px; background-color:#ffffff;">
						        <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">Reset Password</h1>
						        <p style="margin:0;">
						            <p>Ciao <?= esc($firstname) . ' ' . esc($lastname); ?>, è stata richiesta l'impostazione di una nuova password!</p>

						            <p>Ancora un ultimo passo, clicca sul bottone sottostante per impostare la nuova password.</p>

						            <p>Ti ricordiamo il tuo nome utente, che è <b><?= esc($email); ?></b>.</p>

						            <p>Saluti!<br>
						            Lo Staff di <b>Prototype</b>!</p>
						        </p>
						    </td>
						</tr>

<?php break; ?>
<?php case 'recovery': ?>

						<tr>
						    <td style="padding: 30px 30px 0 30px; background-color:#ffffff;">
						        <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">Recovery Password</h1>
						        <p style="margin:0;">
						            <p>Ciao <?= esc($firstname) . ' ' . esc($lastname); ?>, è stata richiesta l'impostazione di una nuova password!</p>

						            <p>Se non sei stato tu ad effettuare questa richiesta ti preghiamo di ignorare questa email, 

						            altrimenti, clicca sul link sottostante per impostare la nuova password.</p>

						            <p>Ti ricordiamo il tuo nome utente, che è <b><?= esc($email); ?></b>.</p>

						            <p>Saluti!<br>
						            Lo Staff di <b>Prototype</b>!</p>
						        </p>
						    </td>
						</tr>

<?php break; ?>
<?php endswitch; ?>

						<tr>
						    <td style="padding:10px 30px 11px 30px; font-size:0; background-color:#ffffff; border-bottom:1px solid #f0f0f5; border-color:rgba(201,201,207,.35); text-align: center;">
						        <!--[if mso]>
						        <table role="presentation" width="100%">
						        <tr>
						        <td style="width:145px;" align="left" valign="top">
						        <![endif]-->
						        <!--[if mso]>
						        </td>
						        <td style="width:395px;padding-bottom:20px;" valign="top">
						        <![endif]-->
						        <div class="col-lge" style="display:inline-block; width:100%; max-width:395px; vertical-align:top; padding-bottom:20px; font-family:'Montserrat', sans-serif; font-size:16px; line-height:22px; color:#363636;">
						            <p style="margin:0;">
						                <a href="<?= base_url('admin/auth/setPassword/' . esc($token)); ?>" style="background: #28a745; text-decoration: none; padding: 10px 25px; color: #ffffff; border-radius: 0; display:inline-block; mso-padding-alt:0;text-underline-color:#28a745"><!--[if mso]><i style="letter-spacing: 25px;mso-font-width:-100%;mso-text-raise:20pt">&nbsp;</i><![endif]--><span style="mso-text-raise:10pt;font-weight:bold;">Impostazione password</span><!--[if mso]><i style="letter-spacing: 25px;mso-font-width:-100%">&nbsp;</i><![endif]-->
						                </a>
						            </p>
						        </div>
						        <!--[if mso]>
						        </td>
						        </tr>
						        </table>
						        <![endif]-->
						    </td>
						</tr>
                    </table>
                    <!--[if mso]>
                    </td>
                    </tr>
                    </table>
                    <![endif]-->
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>