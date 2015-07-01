<?php
echo<<<"OUT"
<form action=" " method="post">
    Заголовок новости:<br />
    <input type="text" name="title" /><br />
    Выберите категорию:<br />
    <select name="category">
      <option value="1">Политика</option>
      <option value="2">Культура</option>
      <option value="3">Спорт</option>
    </select>
    <br />
    Текст новости:<br />
    <textarea name="description" cols="50" rows="5"></textarea><br />
    Источник:<br />
    <input type="text" name="source" /><br />
    <br />
    <input type="submit" value="Добавить!" />
</form>

OUT;

 // <div class="container">

 //      <div id="login">

 //        <h2><span class="fontawesome-lock"></span>Sign In</h2>

 //        <form action="#" method="POST">

 //          <fieldset>

 //            <p><label for="email">E-mail address</label></p>
 //            <p><input type="email" id="email" placeholder="mail@address.com"></p>

 //            <p><label for="password">Password</label></p>
 //            <p><input type="password" id="password" placeholder="password"></p>

 //            <p><input type="submit" value="Sign In"></p>

 //          </fieldset>

 //        </form>

 //      </div> 
 //    </div>

