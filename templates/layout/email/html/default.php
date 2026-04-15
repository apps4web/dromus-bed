<?php
declare(strict_types=1);

/** @var \App\View\AppView $this */

$title = trim((string)$this->fetch('title'));
if ($title === '') {
    $title = 'Dromus Bed & Boetiek';
}

$logoUrl = $this->Url->build('/img/dromus-logo.jpg', ['fullBase' => true]);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($title) ?></title>
    <style>
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0;
            mso-table-rspace: 0;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
            display: block;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100% !important;
            background-color: #f6f3ee;
            color: #3d352f;
            font-family: Georgia, 'Times New Roman', serif;
        }

        .email-wrap {
            width: 100%;
            background: radial-gradient(circle at top, #f9f6f0 0%, #f1ebe2 60%, #ebe4da 100%);
            padding: 28px 12px;
        }

        .email-card {
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e6ddd0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(36, 30, 23, 0.12);
        }

        .brand-bar {
            background: linear-gradient(135deg, #5d6b47 0%, #6f7b59 100%);
            text-align: center;
            padding: 26px 24px 20px;
        }

        .logo {
            width: 88px;
            height: 88px;
            margin: 0 auto 14px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.75);
            object-fit: cover;
        }

        .brand-title {
            margin: 0;
            color: #ffffff;
            font-size: 24px;
            line-height: 1.25;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .brand-tagline {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, 0.88);
            font-size: 13px;
            line-height: 1.4;
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        .content-wrap {
            padding: 28px 28px 24px;
            color: #3d352f;
            font-size: 16px;
            line-height: 1.65;
        }

        .content-wrap h1,
        .content-wrap h2,
        .content-wrap h3 {
            color: #2f2924;
            margin-top: 0;
            line-height: 1.35;
        }

        .content-wrap p {
            margin: 0 0 14px;
        }

        .content-wrap a {
            color: #5d6b47;
            text-decoration: underline;
        }

        .footer {
            padding: 0 28px 26px;
        }

        .footer-line {
            border-top: 1px solid #ece3d7;
            margin: 0;
        }

        .footer-copy {
            margin: 14px 0 0;
            color: #7d746a;
            font-size: 12px;
            line-height: 1.6;
            text-align: center;
        }

        @media screen and (max-width: 600px) {
            .email-wrap {
                padding: 16px 8px;
            }

            .brand-bar {
                padding: 22px 16px 18px;
            }

            .content-wrap {
                padding: 22px 18px 20px;
                font-size: 15px;
                line-height: 1.6;
            }

            .footer {
                padding: 0 18px 20px;
            }

            .brand-title {
                font-size: 21px;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" class="email-wrap">
        <tr>
            <td align="center" valign="top">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" class="email-card">
                    <tr>
                        <td class="brand-bar" align="center" valign="top">
                            <img src="<?= h($logoUrl) ?>" width="88" height="88" alt="Dromus logo" class="logo">
                            <p class="brand-title">Dromus Bed &amp; Boetiek</p>
                            <p class="brand-tagline">Uw thuis weg van huis</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="content-wrap" valign="top">
                            <?= $this->fetch('content') ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer" align="center" valign="top">
                            <hr class="footer-line">
                            <p class="footer-copy">
                                Sint Domusstraat 8, 4301 CP Zierikzee<br>
                                Dromus Bed &amp; Boetiek
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
