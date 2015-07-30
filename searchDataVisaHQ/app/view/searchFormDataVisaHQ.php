<?php require_once('layout.php'); ?>
<body>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-lg-8">
                <form action="searchDataVisaHQ" method="get">
                    <div class="col-sm-4">
                        <input type="text" name="host" class="form-control" placeholder="Enter search address">
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="data" class="form-control" placeholder="Enter search data">
                    </div>
                    <div class="col-sm-2">
                        <ul class="nav nav-pills">
                            <select name="address" class="form-control">
                                <option value="default">Please Select</option>
                                <?php if(isset($address)) : ?>
                                    <?php foreach($address as $key): ?>
                                        <option value="<?=$key['id']; ?>"><?=$key['address']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif;?>
                            </select>
                        </ul>
                    </div>
                    <div class="col-sm-2">
                        <input type="submit" class="btn" onclick="searchData(form); return false;" value="Check">
                    </div>
                </form>
                <a href="addressCheck" style="position: absolute ;right: -20%; top: -3%">
                    <button  class="btn btn-success">Check address table</button>
                </a>
            </div>
        </div>
    </div>
    <table id="example" class="table table-striped table-bordered tablesorter"  data-height="400"
           data-side-pagination="server" data-pagination="true" width="100%" cellspacing="0">

        <thead>
        <tr>
            <th> address </th>
            <th> log_text </th>
            <th> log_time </th>
            <th></th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <th> address </th>
            <th> log_text </th>
            <th> log_time </th>
            <th></th>
        </tr>
        </tfoot>

        <tbody>
        <?php if(isset($logs)): ?>
            <?php foreach($logs as $key => $value):?>
                <tr>
                    <td><?=$value['host_name']; ?></td>
                    <td><?=$value['log_name']; ?></td>
                    <td><?=$date->setTimestamp($value['time_log'])->format('Y-m-d H:i:s'); ?></td>


                    <td>
                        <a href="deleteData?id=<?=$value['id']?>" id="<?=$value['id']?>">
                            <button type="button"  class="btn btn-danger btn-sm" >
                                <span class="glyphicon glyphicon-remove"></span> Remove
                            </button></a>

                    </td>
                </tr>
            <?php endforeach;?>
        <?php endif; ?>
        </tbody>
    </table>

    <div style="position:relative">
        <div id="pager" class="pager" style="top: 30px; position: absolute; ">
            <form>
                <img src="js/icons/first.png" class="first">
                <img src="js/icons/prev.png" class="prev">
                <input type="text" class="pagedisplay">
                <img src="js/icons/next.png" class="next">
                <img src="js/icons/last.png" class="last">
                <select class="pagesize">
                    <option selected="selected" value="10">10</option>
                    <option value="20">20</option>
                    <option value="300">50</option>
                    <option value="400">100</option>
                </select>
            </form>
        </div>
    </div>
</div>

<div style="background: #f0f0f0;">
    <button id="modal_button" type="button" class="btn btn-primary" data-toggle="modal"
            data-target=".bs-example-modal-lg">
        <i id="icon" class="glyphicon glyphicon-resize-full"></i>
    </button>
</div>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="jumbotron">
            <div class="container">
                <form action="record" method="get" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-20">
                            <input type="text" name="address"  class="form-control" placeholder="Enter address">
                        </div></div>
                    <div class="form-group">
                        <div class="col-sm-20">
                            <textarea name="text"  class="form-control" placeholder="Enter text"></textarea>
                        </div></div>
                    <div class="form-group">
                        <div class="col-sm-20">
                            <select name="id_or_class_flag" class="form-control">
                                <option value="default">Please Select id or class</option>
                                <option value="1">id</option>
                                <option value="2">class</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-14">
                            <input type="text"  name="id_class"  class="form-control" placeholder="Enter id or class">
                        </div></div>
                    <div class="form-group">
                        <div class="col-sm-20">
                            <input type="submit" class="btn btn-primary form-control" value="Send" onclick="checkNewData(form); return false;">
                        </div></div>
                </form>
            </div>
        </div>
    </div>
</div>
<br/><br/><br/><br/><br/>
<div class="container-fluid">
    <div class="panel-default">
        <div class="panel-heading">Duplicate tags</div>
        <div class="panel-body">
            <form>
                <label for="Domains">Domains</label>
                <label for="Pages">Pages</label>
                <div class="form-inline">
                    <textarea class="form-control" id="Domains" name="Domains" style="width: 20%;height: 20%;resize: none" ><?=$domains?></textarea>
                    <textarea class="form-control" id="Pages" name="Pages" style="width: 31%;height: 20%;resize: none"><?=$pages?></textarea>
                    <br/><br/>
                </div>
                <div class="form-group">
                    <button class="btn btn-info" name="search" onclick="getDuplicateTags($('#Pages').val(),$('#Domains').val(),'duplicateTags')" type="button">Find duplicate meta tags</button>
                    <img src="js/icons/spinner.gif" id="duplicateTagsSpinner" hidden="hidden" width="5%">
                </div>
            </form>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td style="text-align: center">Duplicate description</td>
                    <td style="text-align: center">Duplicate title</td>
                </tr>
                </thead>
                <tbody id="duplicateTags">
                </tbody>
            </table>
        </div>
    </div>
</div>
<br/><br/><br/><br/><br/>
<div class="container-fluid">
    <div class="panel-default">
        <div class="panel-heading"><h4>HTML errors</h4></div>
        <div class="panel-body">
            <form>
                <label for="Domains">Domains</label>
                <label for="Pages">Pages</label>
                <div class="form-inline">
                    <textarea class="form-control" id="Pages" name="Pages" style="width: 31%;height: 20%;resize: none"><?=$pages?></textarea>
                    <br/><br/>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-info" name="search" onclick="getHtmlErrors($('#Pages').val(),'htmlErrors');">Check HTML errors</button>
                    <img src="js/icons/spinner.gif" id="htmlErrorsSpinner" hidden="hidden" width="5%">
                </div>
            </form>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td style="text-align: center">Page</td>
                    <td style="text-align: center">Error and warnings</td>
                </tr>
                </thead>
                <tbody id="htmlErrors">
                </tbody>
            </table>
        </div>
    </div>
</div>
<br/><br/><br/><br/><br/>
<div class="container-fluid">
    <div class="panel-default">
        <div class="panel-heading"><h4>Robot's check</h4></div>
        <div class="panel-body">
            <form id="robotCheckForm">
                <label for="Domains">Domains</label>
                <label for="Pages">Pages</label>
                <div class="form-inline">
                    <textarea class="form-control" id="Domains0" name="Domains0" style="width: 20%;height: 20%;resize: none" ><?=$checkDomains['0']?></textarea>
                    <textarea class="form-control" id="Robots0" name="Robots0" style="width: 20%;height: 20%;resize: none"><?=$robots['0']?></textarea>
                </div>
                <br/>
                <br/>
                <button type="button" class="btn btn-danger" id="Remove1" onclick="deleteForm('1');"><span class="glyphicon glyphicon-remove-circle"></span></button>
                <div class="form-inline">
                    <textarea class="form-control" id="Domains1" name="Domains1" style="width: 20%;height: 20%;resize: none" ><?=$checkDomains['1']?></textarea>
                    <textarea class="form-control" id="Robots1" name="Robots1" style="width: 20%;height: 20%;resize: none"><?=$robots['1']?></textarea>
                </div>
                <br/><br/>
                <button type="button" class="btn btn-danger" id="Remove2" onclick="deleteForm('2');"><span class="glyphicon glyphicon-remove-circle"></span></button>
                <div class="form-inline">
                    <textarea class="form-control" id="Domains2" name="Domains2" style="width: 20%;height: 20%;resize: none" ><?=$checkDomains['2']?></textarea>
                    <textarea class="form-control" id="Robots2" name="Robots2" style="width: 20%;height: 20%;resize: none"><?=$robots['2']?></textarea>
                    <br/><br/>
                </div>
                <div id="newForms">
                </div>
                <div class="form-group">
                    <button class="btn btn-info" name="search" onclick="getRobotsData()" type="button">Find duplicate meta tags</button>
                    <button class="btn btn-success" type="button" id="create" onclick="createForm()">Create robot check</button>
                    <img src="js/icons/spinner.gif" id="checkRobotsSpinner" hidden="hidden" width="5%">
                </div>
            </form>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td style="text-align: center">Duplicate description</td>
                    <td style="text-align: center">Duplicate title</td>
                </tr>
                </thead>
                <tbody id="checkRobots">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script >
    function getRobotsData(){
        var count = $("button[id^='Remove']").length;
        var robotsArr=[];
        var domainsArr=[];
        var robot,domain,i;
        for(i=0;i<=count;i++){
            robot=$("#Robots"+i).val();
            robotsArr.push(robot);
        }
        for(i=0;i<=count;i++){
            domain=$("#Domains"+i).val();
            domainsArr.push(domain);
        }
        checkRobots(robotsArr,domainsArr,'checkRobots');
    }
</script>
</body>
</html>