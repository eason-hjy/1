<?php
include_once './lib/fun.php';

if(!checkLogin())
{
    msg(2, '请登录', 'login.php');
}


$user = $_SESSION['user'];

//校验 url中商品id

$goodsId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';

//如果id不存在 跳转到商品列表
if(!$goodsId)
{
    msg(2,'参数非法','index.php');
}


//根据商品id查询商品信息
$con = mysqlInit('127.0.0.1', 'root', '', 'imooc_mall');

$sql = "SELECT * FROM `im_goods` WHERE `id` = {$goodsId}";
$obj = mysql_query($sql);

//当根据id查询商品信息为空 跳转商品列表页
if(!$goods = mysql_fetch_assoc($obj))
{
    msg(2,'画品不存在','index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|编辑画品</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><span>管理员：<?php echo $user['username'] ?></span></li>
            <li><a href="#">退出</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="addwrap">
        <div class="addl fl">
            <header>编辑画品</header>
            <form name="publish-form" id="publish-form" action="do_edit.php" method="post"
                  enctype="multipart/form-data">
                <div class="additem">
                    <label id="for-name">画品名称</label><input type="text" name="name" id="name" placeholder="请输入画品名称" value="<?php echo $goods['name']?>">
                </div>
                <div class="additem">
                    <label id="for-price">价值</label><input type="text" name="price" id="price" placeholder="请输入画品价值" value="<?php echo $goods['price']?>" >
                </div>
                <div class="additem">
                    <!-- 使用accept html5属性 声明仅接受png gif jpeg格式的文件                -->
                    <label id="for-file">画品</label><input type="file" accept="image/png,image/gif,image/jpeg" id="file" name="file">
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-des">画品简介</label>
                    <textarea id="des" name="des" placeholder="请输入画品简介"><?php echo $goods['des']?></textarea>
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-content">画品详情</label>
                    <div style="margin-left: 120px" id="container">
                        <textarea id="content" name="content"><?php echo $goods['content']?></textarea>
                    </div>

                </div>
                <div style="margin-top: 20px">
                    <!-- 隐藏商品id 用于提交商品信息 -->
                    <input type="hidden" name="id" value="<?php echo $goods['id']?>">
                    <button type="submit">发布</button>
                </div>

            </form>
        </div>
        <div class="addr fr">
            <img src="./static/image/index_banner.png">
        </div>
    </div>

</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>
<script src="./static/js/kindeditor/kindeditor-all-min.js"></script>
<script src="./static/js/kindeditor/lang/zh_CN.js"></script>
<script>
    var K = KindEditor;
    K.create('#content', {
        width      : '475px',
        height     : '400px',
        minWidth   : '30px',
        minHeight  : '50px',
        items      : [
            'undo', 'redo', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'clearhtml',
            'fontsize', 'forecolor', 'bold',
            'italic', 'underline', 'link', 'unlink', '|'
            , 'fullscreen'
        ],
        afterCreate: function () {
            this.sync();
        },
        afterChange: function () {
            //编辑器失去焦点时直接同步，可以取到值
            this.sync();
        }
    });
</script>

<script>
    $(function () {
        $('#publish-form').submit(function () {
            var name = $('#name').val(),
                price = $('#price').val(),
                file = $('#file').val(),
                des = $('#des').val(),
                content = $('#content').val();
            if (name.length <= 0 || name.length > 30) {
                layer.tips('画品名应在1-30字符之内', '#name', {time: 2000, tips: 2});
                $('#name').focus();
                return false;
            }
            //验证为正整数
            if (!/^[1-9]\d{0,8}$/.test(price)) {
                layer.tips('请输入最多9位正整数', '#price', {time: 2000, tips: 2});
                $('#price').focus();
                return false;
            }

//            if (file == '' || file.length <= 0) {
//                layer.tips('请选择图片', '#file', {time: 2000, tips: 2});
//                $('#file').focus();
//                return false;
//
//            }

            if (des.length <= 0 || des.length >= 100) {
                layer.tips('画品简介应在1-100字符之内', '#content', {time: 2000, tips: 2});
                $('#des').focus();
                return false;
            }

            if (content.length <= 0) {
                layer.tips('请输入画品详情信息', '#container', {time: 2000, tips: 3});
                $('#content').focus();
                return false;
            }
            return true;

        })
    })
</script>
</html>
