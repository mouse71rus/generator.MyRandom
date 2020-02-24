<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Генератор">
    <title>Generator</title>
    
    <link rel="stylesheet" href="/template/font-awesome/css/font-awesome.min.css">
    <link href="/template/css/fa-style.css" rel="stylesheet" type="text/css">
    <link href="/template/css/style.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="/template/js/script.js"></script>
</head>
<body>
    <!-- <h2>Password generator</h2> -->
    <div class="container">
        <div class="header">
            <div class="text">
                Generator
            </div>
        </div>
        <div class="main">
            <div class="row">
                <label for="seed">Начальное значение (<span>Генерируется автоматически</span> <input id="seed_auto" type="checkbox" checked="checked"> )</label>
                <input id="seed" title="Начальное значение" type="text" value="134141358418" pattern="[0-9]+" placeholder="Начальное значение" disabled>
            </div>
            <input type="checkbox" id="advanced" style="margin-left: 5px;"> <label for="advanced">Задать a, c, m вручную</label>
            <div class="row">
                <label for="multiplier">Множитель a</label>
                <input id="multiplier" title="Множитель a" type="text" value="22695477" pattern="[0-9]+" placeholder="Множитель a" disabled>
            </div>
            <div class="row">
                <label for="addend">Приращение c</label>
                <input id="addend" title="Приращение c" type="text" value="1" pattern="[0-9]+" placeholder="Приращение c" disabled>
            </div>
            <div class="row">
                <label for="mask">Модуль m</label>
                <input id="mask" title="Модуль m" type="text" value="4294967296" pattern="[0-9]+" placeholder="Модуль m" disabled>
            </div>

            <input type="checkbox" id="unlimited" style="margin-top: 10px; margin-left: 5px;" checked="checked"> <label for="unlimited">Неограниченый диапазон генерируемых чисел</label>
            <div class="row">
                <label for="left">Левая граница</label>
                <input id="left" title="Левая граница" type="text" value="0" pattern="[0-9]+" placeholder="Левая граница" disabled>
            </div>

            <div class="row">
                <label for="right">Правая граница</label>
                <input id="right" title="Правая граница" type="text" value="1000" pattern="[0-9]+" placeholder="Правая граница" disabled>
            </div>

            <div class="row">
                <label for="count">Количество интераций</label>
                <table class="range">
                    <tr>
                        <td class="val">1</td>
                        <td><input type="range" min="1" max="9999999" value="200"></td>
                        <td class="val">9999999</td>
                        <td><input type="text" id="count" minlength="1" value="200" maxlength="10" pattern="[0-9]+" required></td>
                    </tr>
                </table>
            </div>
            <div class="row items mod">
                <label>Режим</label>
                <span class="item selected" data-mode="Line">Метод линейного конгруэнта</span>
                <span class="item" data-mode="Fib">Метод Фибоначчи с запаздываниями</span>
                <span class="item" data-mode="Pi">Метод Монте-Карло для вычисления числа π</span>
            </div>


            <div class="row items print_mod">
                <label>Вывод</label>
                <span class="item selected" data-mode="File">В файл</span>
                <span class="item" data-mode="Screen">На экран</span>
            </div>

           
            
        </div>
        
        
        <div class="progress-pie-chart" id="progress-pie-chart" data-percent="0">
          <div class="ppc-progress">
            <div class="ppc-progress-fill"></div>
          </div>
          <div class="ppc-percents">
            <div class="pcc-percents-wrapper">
              <span>0%</span>
            </div>
          </div>
        </div>

        <a href="#" class="generate">Генерировать</a>
    </div>
    <div style="background: white;">
        <textarea id="sec" style="margin: 15px 15px; height: 100px; resize: vertical; padding: 7px;"></textarea>
    </div>
</body>
</html>