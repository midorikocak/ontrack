<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
    <style type="text/css">
        @media only screen and (max-width : 640px) {

            body{
                margin-top: 150px;
                margin-bottom: 150px;
            }

            table[class="container"] {
                width: 98% !important;
            }
            td[class="bodyCopy"] p {
                padding: 0 15px !important;
                text-align: left !important;
            }
            td[class="spacer"] {
                width: 15px !important;
            }
            td[class="belowFeature"] {
                width: 95% !important;
                display: inline-block;
                padding-left: 15px;
                margin-bottom: 20px;
            }
            td[class="belowFeature"] img {
                float: left;
                margin-right: 15px;
            }

            table[class="belowConsoles"] {
                width: 100% !important;
                display: inline-block;
            }

            table[class="belowConsoles"] img {
                margin-right: 15px;
                margin-bottom: 15px;
                float: left;
            }


        }

        @media only screen and (min-width: 481px) and (max-width: 560px) {

            td[class="Logo"] {
                width: 560px !important;
                text-align: center;
            }

            td[class="viewWebsite"] {
                width: 560px !important;
                height: inherit !important;
                text-align: center;
            }

        }

        @media only screen and (min-width: 250px) and (max-width: 480px) {

            td[class="Logo"] {
                width: 480px !important;
                text-align: center;
            }

            td[class="viewWebsite"] {
                width: 480px !important;
                height: inherit !important;
                text-align: center;
            }

            td[class="spacer"] {
                display: none !important;
            }

            td[class="bodyCopy"] p {
                padding: 0 15px !important;
                text-align: left !important;
            }

            td[class="bodyCopy"] h1 {
                padding: 0 10px !important;
            }

            h1, h2 {
                line-height: 120% !important;
            }

            td[class="force-width"] {width: 98% !important; padding: 0 10px;}

            [class="consoleImage"] {
                display: inline-block;
            }

            [class="consoleImage"] img {
                text-align: center !important;
                display: inline-block;
            }

            table[class="belowConsoles"] {
                text-align: center;
                float: none;
                margin-bottom: 15px;
                width: 100% !important;
            }

            table[class="belowConsoles"] img {
                margin-bottom: 0;
            }

        }
    </style>
</head>

<body bgcolor="#f6f6f6" style="font-family: Arial; background-color: #f6f6f6;">
<table width="630" class="container" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table align="left">
                <tr>
                    <td width="188" class="Logo">

                    </td>
                </tr>
            </table>
            <table align="right">
                <tr>
                    <td height="70" class="viewWebsite">

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="630" bgcolor="#fcfcfc" style="border: 1px solid #dddddd; line-height: 135%;" class="container" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td bgcolor="#fcfcfc" colspan="3" width="100%" height="10">&nbsp;</td>
    </tr>
    <tr>
        <td bgcolor="#fcfcfc" colspan="3" align="center">
            <img src="http://www.rickygipson.com/images/codepen/main-image.jpg" width="100%">
        </td>
    </tr>
    <tr>
        <td colspan="3" height="15">&nbsp;</td>
    </tr>
    <tr>
        <td bgcolor="#fcfcfc" colspan="3">
            <table>
                <tr>
                    <td width="30" class="spacer">&nbsp;</td>
                    <td align="center" class="bodyCopy">
                        <?= $this->fetch('content') ?>
                    <td width="30" class="spacer">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center">
            <img src="http://www.rickygipson.com/images/codepen/grey_div.jpg" width="95%">
        </td>
    </tr>
    <tr>
        <td colspan="3" height="3">&nbsp;</td>
    </tr>
</table>
<table width="630" class="container" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table align="left">
                <tr>
                    <td width="188" class="Logo">

                    </td>
                </tr>
            </table>
            <table align="right">
                <tr>
                    <td height="70" class="viewWebsite">

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


</body>
</html>
