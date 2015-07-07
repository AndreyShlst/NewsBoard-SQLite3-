<style>
    #createButton {
        display:none !important;
    }
</style>
<?php
echo<<<"OUT"
<div class="create_news">
    <form action=" " method="post" id = "addNews">
       <label class="field__label">Заголовок новости:</label>
       <div class="field__desc">Сформулируйте заголовок так, чтобы сразу было понятно, о чём речь.</div>
        <input type="text" name="title" />
        <label class="field__label">Выберите категорию:</label>
        <div class="field__desc">Выберите,наиболее подходящую категорию для Вашей новости.</div>
        <select name="category">
          <option value="1">Политика</option>
          <option value="2">Культура</option>
          <option value="3">Спорт</option>
          <option value="4">IT</option>
        </select>
       <label class="field__label">Текст новости:</label>
       <div class="field__desc">Опишите,что произошло.</div>
        <textarea name="description" cols="50" rows="5"></textarea>
        <label class="field__label">Источник</label>
        <div class="field__desc">Укажите ссылку на оригинальный контент.Никто не любит пиратов:)</div>
        <input type="text" name="source" />
    </form>
</div>
OUT;

