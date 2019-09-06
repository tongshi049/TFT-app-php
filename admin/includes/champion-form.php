<?php if (!empty($champion->errors)): ?>
    <ul>
        <?php foreach ($champion->errors as $error): ?>
            <li><?=$error?></li>
        <?php endforeach;?>
    </ul>
<?php endif;?>

<div class="row row-content">
    <div class="col-8">
    <form method="post" id="formChampion">

        <div class="form-group row">
            <label for="name" class="col-2">Name</label>
            <div class="col-6">
            <input name="name" class="form-control" id="name" placeholder="Champion name" value="<?=htmlspecialchars($champion->name);?>">
            </div>
        </div>

        <div class="form-group row">
            <label for="description" class="col-2">Description</label>
            <div class="col-6">
            <textarea name="description" class="form-control" rows="4" cols="40" id="description" placeholder="Chamption description"><?=htmlspecialchars($champion->description);?></textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="published_at" class="col-2">Publication date</label>
            <div class="col-6">
            <input type="date" name="published_at" class="form-control" id="published_at" value="<?=htmlspecialchars($champion->published_at);?>">
            </div>
        </div>

        <div class="">
            <div class="col-8 row"><h4>Categories</h3><hr></div>

            <div class="col-8 row mt-10">
                <?php foreach ($categories as $category): ?>
                    <span class="form-check col-3">
                        <input class="form-check-input" type="checkbox" name="category[]" value="<?=$category['id'];?>"
                            id="<?=$category['id'];?>"
                            <?php if (in_array($category['id'], $category_ids)): ?>
                                checked
                                <?php endif;?>
                            >
                        <label class="form-check-label" for="<?=$category['id'];?>"><?=htmlspecialchars($category['name']);?></label>
                    </span>
                <?php endforeach;?>
            </div>
        </div>


        <div class="offset-2 mt-10"><button class="btn btn-primary">Save</button></div>

    </form>
    </div>
</div>



