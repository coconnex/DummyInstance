<style>
    .show {
        display: block;
    }

    .hide {
        display: none;
    }

    .border {
        border: 1px solid #D5D8DC;
        border-radius: 5px;
    }

    .box {
        margin: inherit;
        text-align: center;
        height: inherit;
    }

    .boxleft {
        margin: inherit;
        /* height: inherit; */
    }

    .w30 {
        width: 100%;
        max-width: 175px;
    }

    .w40 {
        width: 100%;
        max-width: 425px;
        /* overflow-x: scroll; */
    }

    .w20 {
        min-width: 20%;
    }

    .w10 {
        min-width: 125px;
        width: 10%;
    }

    .w5 {
        min-width: 64px;
        width: 5%;
    }

    .w3 {
        width: 100%;
        min-width: 45px;
        width: 3%;
    }

    .codes {
        /* display: flex; */
        height: inherit;
    }

    .color {
        width: 70%;
        text-align: center;
    }

    .color_code {
        width: 30%;
        text-align: end;
        height: inherit;
        border-radius: 3px;
        box-shadow: 1px 1px 3px 2px #cccccc;
    }

    .boxrow {
        clear: both;
        height: 40px;
        margin: 5px 5px;
    }

    .txt {
        margin: 10px;
    }

    .bold {
        font-weight: bold;
    }

    .left {
        float: left;
    }

    .right {
        float: right;
    }

    .hand {
        cursor: pointer;
    }

    .btnback {
        background-color: #f0f0f0;
    }

    tr {
        border: 1px solid #c0c0c0;
        margin-bottom: 5px !important;
    }

    tr>td {
        border-right: 1px solid #c0c0c0;
    }

    .force-overflow {
        min-height: 450px;
    }

    .grid {
        position: relative;
        width: 100%;
        margin-bottom: 3%;
        border-radius: 0.6em
    }

    .grid-container {
        overflow-y: auto;
        height: auto;
        max-height: 450px;
    }
    .active {
        color: #000 !important;
    }
    

    tbody {
        text-align: center !important;
    }

    table {
        border-spacing: 0;
        width: 100%;
    }

    td,
    th {
        border-bottom: 1px solid #bfb6b6;
        color: #000;
        padding: 5px 5px;
        text-align: left;
    }

    th {
        height: 0;
        line-height: 0;
        padding-top: 0;
        padding-bottom: 0;
        border: none;
        white-space: nowrap;
        text-align: center !important;
        color: #000;
    }

    th div {
        position: absolute;
        background: transparent;
        color: #fff;
        padding: 9px 25px;
        top: 0;
        margin-left: -25px;
        line-height: normal;
        border-left: 1px solid #800;
    }

    
    .header th {
        border-bottom: 3px solid #ccc !important;
    }

    .header {
        background: #ededed !important;
    }

    /* tab css start */
    #checks_tabs {
        margin-left: 20px;
        background: #fff;
        display: flex;
        justify-content: flex-start;
    }
</style>

<div id="divallocatezone">
    <!-- Tabs navs -->
    <ul class="nav nav-tabs mb-3" id="checks_tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a data-mdb-tab-init class="nav-link active" id="ex1-tab-1" href="#prelive_tab" role="tab" aria-controls="ex1-tabs-1" aria-selected="true">Prelive</a>
        </li>
        <li class="nav-item" role="presentation">
            <a data-mdb-tab-init class="nav-link" id="ex1-tab-2" href="#admin_tab" role="tab" aria-controls="ex1-tabs-2" aria-selected="false">Admin</a>
        </li>
        <li class="nav-item" role="presentation">
            <a data-mdb-tab-init class="nav-link" id="ex1-tab-3" href="#monitor_tab" role="tab" aria-controls="ex1-tabs-3" aria-selected="false">Monitor</a>
        </li>
    </ul>
    <!-- Tabs navs -->
    <form method="post" name="frmallocations">
        <div class="boxrow">
            <div class="box left w3">
                <div class="txt bold">#</div>
            </div>
            <div class="boxleft left w30">
                <div class="txt bold ">Check Specification</div>
            </div>
            <div class="boxleft left w30">
                <div class="txt bold ">What is expected</div>
            </div>
            <div class="boxleft left w40">
                <div class="txt bold ">Check Value</div>
            </div>
            <div class="box left w3">
                <div class="txt bold ">Counts</div>
            </div>
        </div>

        <input type="hidden" name="txtremovealloc" value="" />
        <input type="hidden" name="txtaddsection" value="" />
        <input type="hidden" name="txtaddsectioncolor" value="" />
        <input type="hidden" name="txtremall" value="" />
        <input type="hidden" name="txtaddall" value="" />
    </form>
    <!-- Tabs content -->
    <div class="tab-content" id="checks_tabs_content">
        <div class="tab-pane fade show active" id="prelive_tab" role="tabpanel" aria-labelledby="ex1-tab-1">

            <?php
            //  debug($vars,1);
            $idx = 0;
            foreach ($vars['checks'] as $idx => $subarray) {
                if ($subarray['typeid'] == 1) {
            ?>
                    <div class="boxrow">
                        <div class="box left border w3">
                            <div class="txt"><?php echo $idx; ?></div>
                        </div>
                        <div class="boxleft left border w30">
                            <div class="txt"><?php echo $subarray['description']; ?></div>
                        </div>
                        <div class="boxleft left border w30">
                            <div class="txt"><?php echo $subarray['expected']; ?></div>
                        </div>
                        <div class="boxleft left border w40">
                            <div class="txt"><?php echo $subarray['value']; ?></div>
                        </div>
                        <div class="box left border w3">
                            <div class="txt"><?php echo $subarray['counts']; ?></div>
                        </div>

                    </div>
            <?php
                }
            }
            ?>
        </div>

        <div class="tab-pane fade" id="admin_tab" role="tabpanel" aria-labelledby="ex1-tab-2">

            <?php
            //  debug($vars,1);
            $idx = 0;
            foreach ($vars['checks'] as $idx => $subarray) {
                if ($subarray['typeid'] == 2) {
            ?>
                    <div class="boxrow">
                        <div class="box left border w3">
                            <div class="txt"><?php echo $idx; ?></div>
                        </div>
                        <div class="boxleft left border w30">
                            <div class="txt"><?php echo $subarray['description']; ?></div>
                        </div>
                        <div class="boxleft left border w30">
                            <div class="txt"><?php echo $subarray['expected']; ?></div>
                        </div>
                        <div class="boxleft left border w40">
                            <div class="txt"><?php echo $subarray['value']; ?></div>
                        </div>
                        <div class="box left border w3">
                            <div class="txt"><?php echo $subarray['counts']; ?></div>
                        </div>

                    </div>
            <?php
                }
            }
            ?>
        </div>
        <div class="tab-pane fade" id="monitor_tab" role="tabpanel" aria-labelledby="ex1-tab-3">

            <?php
            //  debug($vars,1);
            $idx = 0;
            foreach ($vars['checks'] as $idx => $subarray) {
                if ($subarray['typeid'] == 3) {
            ?>
                    <div class="boxrow">
                        <div class="box left border w3">
                            <div class="txt"><?php echo $idx; ?></div>
                        </div>
                        <div class="boxleft left border w30">
                            <div class="txt"><?php echo $subarray['description']; ?></div>
                        </div>
                        <div class="boxleft left border w30">
                            <div class="txt"><?php echo $subarray['expected']; ?></div>
                        </div>
                        <div class="boxleft left border w40">
                            <div class="txt"><?php echo $subarray['value']; ?></div>
                        </div>
                        <div class="box left border w3">
                            <div class="txt"><?php echo $subarray['counts']; ?></div>
                        </div>

                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
    <!-- Tabs content -->


</div>
<!-- ************1st row end*********************** -->

<script>
    let preliveTab = document.getElementById("ex1-tab-1")
    let checkTabs = document.getElementById("checks_tabs")
    var btns = document.getElementsByClassName("nav-link");
    let adminTab = document.getElementById("ex1-tab-2")
    let monitorTab = document.getElementById("ex1-tab-3")
    let preliveTabContent = document.getElementById("prelive_tab")
    let adminTabContent = document.getElementById("admin_tab")
    let monitorTabContent = document.getElementById("monitor_tab")
    adminTabContent.classList.add("hide")
    monitorTabContent.classList.add("hide")
    preliveTab.addEventListener("click", function() {

        preliveTabContent.classList.remove("hide")
        monitorTabContent.classList.add("hide")
        adminTabContent.classList.add("hide")
    })

    adminTab.addEventListener("click", function() {

        adminTabContent.classList.remove("hide")
        preliveTabContent.classList.add("hide")
        monitorTabContent.classList.add("hide")
    })

    monitorTab.addEventListener("click", function() {

        monitorTabContent.classList.remove("hide")
        adminTabContent.classList.add("hide")
        preliveTabContent.classList.add("hide")
    })

    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function() {
            var current = document.getElementsByClassName("active");
            current[0].className = current[0].className.replace(" active", "");
            this.className += " active";
        });
    }
</script>