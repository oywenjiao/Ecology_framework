<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <title>{$title}</title>
    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        form {
            width: 100%;
            height: 100%;
        }

        .block {
            width: 100%;
            height: 1px;
        }

        .title {
            width: 76%;
            height: 20%;
            display: block;
            margin: 5% auto 6%;
        }

        .box {
            width: 80%;
            height: 40%;
            background: #fff;
            border-radius: 1rem;
            margin: 0 auto;
        }

        .box .head {
            font-size: 16px;
            color: #888888;
            text-align: center;
            width: 100%;
            margin: 10.5% auto 11.7%;
        }

        .box .code {
            width: 80%;
            height: 16%;
            display: block;
            margin: 0 auto;
            border-radius: 5px;
            border: 1px solid #888;
            outline: none;
            text-align: center;
        }

        .box .tixian {
            width: 80%;
            height: 20%;
            display: block;
            margin: 11% auto 0;
            background-image: linear-gradient(-90deg, #FC7E29 0%, #F7B11C 100%);
            border-radius: 6px;
            text-align: center;
            border: 0;
            outline: none;
            padding: 0;
            font-size: 20px;
            color: #FFFFFF;
        }

        .tips-box {
            width: 80%;
            margin: 7.2% auto 0;
        }

        .head-text {
            font-size: 15px;
            color: #FFFFFF;
            font-weight: bold;
            margin-bottom: 0.7%;
        }

        .tips-text {
            font-size: 14px;
            color: #FFFFFF;
            line-height: 22px;
        }
    </style>
</head>
<body>
<div class="block"></div>
<div class="box">
    <form method="post" action="/postCode" onsubmit="return false;" id="form">
        <div class="block"></div>
        <div class="head">请输入提现码</div>
        <input type="text" class="code" name="code">
        <button class="tixian" type="submit">提现</button>
    </form>
</div>
<div class="tips-box">
    <div class="head-text">温馨提示：</div>
    <div class="tips-text">1.金额满1块即可提现</div>
    <div class="tips-text">2.如忘记提现码，可从提现记录里查询</div>
    <div class="tips-text">3.提现金额将自动发放，最晚7个工作日内发放</div>
    {*<div class="tips-text">4. 金额满1块即可提现</div>*}
</div>
</body>
<script>

</script>
</html>