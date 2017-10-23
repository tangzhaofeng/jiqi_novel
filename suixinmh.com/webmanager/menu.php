  <!-- sidebar start -->
  <div class="admin-sidebar">
    <ul class="am-list admin-sidebar-list">
      <li><a href="admin-index.php"> 后台管理首页</a></li>
      <li><a href="admin-payment.php"> 渠道充值统计</a></li>
        <?
        if ($_SESSION['qd_admin_level']>=99) {
        ?>
<!--      <li><a href="copy_books.php"> 复制书籍</a></li>-->
        <li><a href="admin-qduser.php"> 推广专员设置</a></li>
      <li><a href="admin-cp.php"> cp账号管理</a></li>
      <li><a href="admin-cppay.php"> cp收入管理</a></li>

        <?
        }
        ?>
      <li><a href="logout.php"> 注销</a></li>
    </ul>
</div>
  <!-- sidebar end -->