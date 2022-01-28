<html>

<head>
    <title>SERKOM</title>
    <style>
        html,
        body {
            height: 100%;
            font-family: 'Share Tech Mono', monospace;
            font-size: 16px;
            color: #38fd3a;
            padding: 0;
            margin: 0;
            line-height: 1.5em;
        }

        body {
            background-color: #000000;
        }

        p {
            margin: 0;
            text-shadow: 0 0 2px rgba(56, 253, 56, 1);
        }

        $input-size: 50px;
        $margin-size: 50px;


        #screen {
            position: absolute;
            top: 0;
            right: 0;
            bottom: $input-size;
            left: 0;
            margin-left: $margin-size;
            margin-right: $margin-size;
            margin-top: $margin-size * .5;
            overflow: auto;
        }

        #ihm {
            position: absolute;
            bottom: 0;
            height: $input-size;
            left: 0;
            right: 0;
            padding-left: $margin-size;
            padding-right: $margin-size;
        }

        #input-indicator {
            line-height: $input-size;
            float: left;
        }

        #input-render {
            height: $input-size;

            .input-render-content {
                padding-left: 1.5em;
                line-height: $input-size;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                width: auto;
                display: block;
            }
        }

        #caret {
            margin-bottom: -0px;
            display: inline-block;
            animation-duration: .5s;
            animation-name: caret-behavior;
            animation-iteration-count: infinite;

            &.inactive {
                display: none;
            }
        }

        @keyframes caret-behavior {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li {
            cursor: pointer;
            box-shadow: 0 0 2px rgba(56, 253, 56, 0);
            text-shadow: 0 0 2px rgba(56, 253, 56, 1);
            transition: color .2s ease, background-color .2s ease, box-shadow .2s ease, text-shadow .2s ease;

            &:hover {
                background-color: #38fd3a;
                color: #000000;
                box-shadow: 0 0 2px rgba(56, 253, 56, 1);
                text-shadow: 0 0 2px rgba(0, 0, 0, .3);
            }
        }
    </style>
</head>

<body>
    <div id="container">
        <div id="screen">
            <p>MENU PILIHAN</p>
            <ul id="main-menu">
                <li>A. Input Angka</li>
                <li>B. Sorting</li>
                <li>C. Searching</li>
                <li>D. Selesai</li>
            </ul>
            <div id="dataset"></div>
            <div id="resultset"></div>
        </div>
        <div id="ihm">
            <input type="hidden" id="choices">
            <span id="input-indicator">Masukan Pilihan [A/B/C/D] > </span>
            <div id="input-render">
                <p class="input-render-content"><span id="intent"></span><span class="inactive" id="caret">▋</span>
                </p>
            </div>
        </div>
    </div>
</body>
<script>
    let arr_number = [];
    let choice = null;
    let task = 0;
    let $intent = document.querySelector("#intent");
    let $caret = document.querySelector("#caret");
    let $choices = document.querySelector("#choices");
    let $indicator = document.querySelector("#input-indicator");
    let $menu = document.querySelector("#main-menu");
    let $dataset = document.querySelector("#dataset");
    let $resultset = document.querySelector("#resultset");

    document.addEventListener('keydown', function(event) {
        if (event.keyCode == 8) {
            $intent.innerHTML = $intent.innerHTML.replace(/(\s+)?.$/, '');
        } else if (event.keyCode == 13 || event.keyCode == 27) {
            $choices.value = $intent.innerHTML;
            $intent.innerHTML = '';

            user_type($choices.value);

        } else {
            let inp = String.fromCharCode(event.keyCode);
            if (/[a-zA-Z0-9-_ ]/.test(inp)) {
                $intent.innerHTML += event.key
            }
        }
    })

    window.onfocus = function() {
        $caret.classList.remove('inactive')
    }
    window.onblur = function() {
        $caret.classList.add('inactive');
        console.log('1');
    }


    
    
    function user_type(val) {
        if (val == "A") {
            choice = "A";
            $indicator.innerHTML = "Masukkan jumlah nilai tugas : ";
        } else if (val == "B") {
            choice = "B";
            arr_number.sort(function(a, b) {return a - b});
            $resultset.innerHTML = "Hasil Sorting : " + arr_number;
        } else if (val == "C") {
            choice = "C";
            $indicator.innerHTML = "Masukan Angka yang dicari : ";
        } else if (val == "D") {
            choice = "D";
            $indicator.innerHTML = "Masukan Pilihan [A/B/C/D] : ";
            $resultset.innerHTML =  $dataset.innerHTML = "";
            arr_number = [];
        }
        if (!isNaN(val)) {
            if (choice == "A") {
                if ($indicator.innerHTML == "Masukkan jumlah nilai tugas : ") {
                    arr_number = [];
                    task = val;
                } else {
                    if (task > 0) {
                        arr_number.push(val);
                        $dataset.innerHTML = "Angka : " + arr_number;
                    }
                    task--;
                }
                if (task > 0) {
                    $indicator.innerHTML = "Masukkan angka : ";
                } else {
                    $indicator.innerHTML = "Masukan Pilihan [A/B/C/D] : ";
                }
                console.log(task);

            } else if (choice == "C") {
                if (arr_number.indexOf(val) !== -1) {
                    $resultset.innerHTML = "Angka ditemukan";
                } else {
                    $resultset.innerHTML = "Angka tidak ditemukan";
                }
            }
        }
    }
    
    
    
    
    
    
    
</script>

</html>