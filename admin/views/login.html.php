<div class="row">
  <div class="col-sm-6 col-md-4 col-md-offset-4">
    <div class="auth-form-wrapper">
      <h1 class="text-center login-title">Вход в панель управления ботом</h1>
      <div class="account-wall">
        <form action="index.php?r=main/login" method="POST" class="form-signin">
          <div class="form-group">
            <input type="text" name="login" class="form-control required-field" placeholder="E-mail" required autofocus>
            <?php if ($params['wrongLoginOrPass']): ?>
              <div class="server-error">Неправильный логин или пароль</div>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <input type="password" name="password" class="form-control required-field" placeholder="Пароль" required>
          </div>
          <button class="btn btn-lg btn-primary btn-block btn-submit" type="submit">
            Войти
          </button>
        </form>
      </div>
      <div class="get-new-pass">
        Забыли пароль? <a href="index.php?r=main/login&setNewPass=true" class="text-center new-account">Выслать пароль на Email</a>.
      </div>
    </div>
    <div class="powered-by">
      powered by <a href="https://njdstudio.com" target="_blank"><img src="assets/img/njd-logo.png" alt=""> Studio</a>
    </div>
  </div>
</div>