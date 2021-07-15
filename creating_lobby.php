<?php require_once('header.php')?>

<div class="block lobby">
    <div class="container">
        <div class="block_inner lobby_inner">
            <h1 class="lobby_h1">Начать игру</h1>
            <div class="lobby_form">
                Найти лобби по id:
                <input type="text" class="block_input lobby_input lobby_findid" placeholder="Введите id лобби">
                <a href="" class="block_button find_lobby_btn">Поиск</a>
                <br>
                Создать лобби:
                <br>
                <input type="text" class="block_input lobby_input lobby_creator_nickname" placeholder="Ваш ник в игре">
                Количество игроков:
                <select name="" id="" class="plnum_select">
                    <option value="5" class="plnum_option">5</option>
                    <option value="6" class="plnum_option">6</option>
                    <option value="7" class="plnum_option">7</option>
                    <option value="8" class="plnum_option">8</option>
                    <option value="9" class="plnum_option">9</option>
                    <option value="10" class="plnum_option">10</option>

                </select>
                <br>
            </div>
            <a href="" class="block_button create_lobby_btn">Создать лобби</a>

        </div>
    </div>
</div>

<?php require_once('footer.php')?>