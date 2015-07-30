<?php require_once('layout.php'); ?>
<body>
<div class="row">
<table id="example" class="table table-striped table-bordered tablesorter"  data-height="400"
       data-side-pagination="server" data-pagination="true" width="100%" cellspacing="0">

    <thead>
    <tr>
        <th> address </th>
        <th> text </th>
        <th></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th> address </th>
        <th> text </th>
        <th></th>
    </tr>
    </tfoot>

    <tbody>
    <?php if(isset($address)): ?>
        <?php foreach($address as $key => $value):?>
            <tr>
                <td><?=$value['address']; ?></td>
                <td><?=$value['text']; ?></td>


                <td style="width: 15%">
                    <div style="position: relative"><a href="#modal" class="btn btn-info btn-sm" data-toggle="modal"
                                                       data-target="#basicModal<?=$value['id']?>"> <button type="button"  class="btn btn-sm" ><i
                                class="glyphicon glyphicon-eye-open"></i></button></a>

                    <a href="deleteDataAddress?id=<?=$value['id']?>" id="<?=$value['id']?>" style="position: absolute;left: 70%">
                        <button type="button"  class="btn btn-danger btn-sm" >
                            <span class="glyphicon glyphicon-remove"></span> Remove
                        </button></a>
</div>
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

<?php if(isset($address)): ?>
<?php foreach($address as $key => $value): ?>
<div class="modal fade bs-example-modal-lg" id="basicModal<?=$value['id']?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="jumbotron">
            <div class="container">
                <form action="changeAddress" method="get" class="form-horizontal">
                    <input type="hidden" name="id" value="<?=$value['id'] ?>">
                    <div class="form-group">
                        <div class="col-sm-20">
                            <input type="text" name="address"  class="form-control" placeholder="Enter address" value="<?=$value['address'] ?>">
                        </div></div>
                    <div class="form-group">
                        <div class="col-sm-20">
                            <textarea name="text"  class="form-control" placeholder="Enter text" ><?=$value['text'] ?></textarea>
                        </div></div>
                    <div class="form-group">
                        <div class="col-sm-20">
                            <select name="id_or_class_flag" class="form-control">
                                <?php if($value['id_or_class_flag']==0) : ?>
                                <option value="default">Please Select id or class</option>
                                <option value="1">id</option>
                                <option value="2">class</option>
                                <?php elseif($value['id_or_class_flag']==1) : ?>
                                <option value="1">id</option>
                                <option value="2">class</option>
                                <option value="default">Nothing</option>
                                <?php elseif($value['id_or_class_flag']==2) : ?>
                                <option value="2">class</option>
                                <option value="1">id</option>
                                <option value="default">Nothing</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-14">
                            <input type="text"  name="id_class"  class="form-control" placeholder="Enter id or class" value="<?=$value['id_class'] ?>">
                        </div></div>
                    <div class="form-group">
                        <div class="col-sm-20">
                            <input type="submit" class="btn form-control" value="Send">
                        </div></div>
                </form>
            </div>
        </div>
    </div>
</div>
    <?php endforeach;?>
<?php endif; ?>
</body>
</html>