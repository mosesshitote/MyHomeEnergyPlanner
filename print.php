<?php
defined('EMONCMS_EXEC') or die('Restricted access');
global $path,$app_color,$app_title,$app_description;
$d = $path . "Modules/assessment/";

$projectid = (int) $_GET['id'];

global $reports;
?>        

<link href='https://fonts.googleapis.com/css?family=Ubuntu:300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo $d; ?>style.css">

<script language="javascript" type="text/javascript" src="<?php echo $d; ?>js/openbem-r4.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $d; ?>js/ui-helper-r3.js"></script>
<!--<script language="javascript" type="text/javascript" src="<?php echo $d; ?>js/ui-openbem-r3.js"></script>-->

<script language="javascript" type="text/javascript" src="<?php echo $d; ?>js/library-r6.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $d; ?>js/model/datasets-r5.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $d; ?>js/model/model-r10.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $d; ?>js/model/appliancesPHPP-r1.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $d; ?>graph-r3.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $d; ?>js/targetbar-r3.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $d; ?>js/vectormath-r3.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $d; ?>js/arrow-r3.js"></script>

<script language="javascript" type="text/javascript" src="<?php echo $d; ?>js/canvas-barchart/barchart.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $d; ?>js/targetbar-carboncoop.js"></script>
<link rel="stylesheet" href="<?php echo $d; ?>js/magnific-popup/magnific-popup.css">
<script src="<?php echo $d; ?>js/magnific-popup/jquery.magnific-popup.min.js"></script>

<style>
    :root {
        --app-color: <?php echo $app_color;?>;
    }
    
    .cc {
        color: var(--app-color);
        font-weight: bold;
        padding-right:20px;
        
    }

    .title {
        padding: 10px 30px;
        color:#888;
        float:left;
    }
    .modal-backdrop
    {
        opacity:0.3 !important;
    }
    body .modal {
        /* new custom width */
        width: 560px;
        /* must be half of the width, minus scrollbar on the left (30px) */
        margin-left: -280px;
    }
</style>

<link rel="stylesheet" href="<?php echo $d; ?>carbon.css">
<script type="text/javascript" src="<?php echo $d; ?>data.js"></script>


<div id="openbem">
    <div id="right-pane">
        <div id="bound">
            <div id="content"></div>
        </div>
    </div>
</div>



</body>
</html>                                		


<script>

    var printmode = true;

    var changelog = "";
    var selected_library = -1;
    var selected_library_tag = "Wall";

    $("#openbem").css("background-color", "#eee");

    var path = "<?php echo $path; ?>";
    var jspath = path + "Modules/assessment/";

    //var c=document.getElementById("rating");
    //var ctx=c.getContext("2d");

    load_view("#topgraphic", 'topgraphic');

    var projectid = <?php echo $projectid; ?>;
    var p = openbem.get(projectid);

    $("#project-title").html(p.name);
    $("#project-description").html(p.description);
    $("#project-author").html(p.author);

    if (p.data == false || p.data == null)
        p.data = {'master': {}};
    var project = p.data;





    var keys = {};

    for (s in project) {
        project[s] = calc.run(calc.run(project[s]));
        $("." + s + "_sap_rating").html(project[s].SAP.rating.toFixed(0));
    }

    var tmp = (window.location.hash).substring(1).split('/');
    var page = tmp[1];
    var scenario = tmp[0];
    if (!scenario)
        scenario = "master";
    if (!page)
        page = "context";

    if (project[scenario] == undefined)
        scenario = 'master';
    data = project[scenario];

    load_view("#content", page);
    InitUI();
    UpdateUI(data);
    // draw_openbem_graphics();

    $(window).on('hashchange', function () {
        var tmp = (window.location.hash).substring(1).split('/');
        page = tmp[1]; //scenario = tmp[0];

        data = project[scenario];

        load_view("#content", page);
        InitUI();
        UpdateUI(data);
    });

    function update()
    {
        console.log("updating");
        project[scenario] = calc.run(project[scenario]);
        data = project[scenario];

        UpdateUI(data);
        draw_openbem_graphics();

        $("." + scenario + "_sap_rating").html(project[scenario].SAP.rating.toFixed(0));

        openbem.set(projectid, project, function (result) {
            alertifnotlogged(result);
        });
    }

</script>
