<div class="container">
    <?php $this->load->view('partials/breadcrumbs'); ?>
</div>
<div class="container">
    <div class="col-sm-12">
            <?php
            if (!empty($search)) {
                ?>
                <h4 class="naglowek-podstrona" ><?= (new CustomElementModel('10'))->getField('wyszukiwarka'); ?></h4>

                <?php
            } else {
                ?>
                <h4 class="naglowek-podstrona" ><?= $category->name; ?></h4>             
                <?= $category->body; ?>
            <?php }
            ?>

        <?php
        if (!empty($cat_children) && empty($search)) {
            ?>
            <section class="sekcja-oferta">
                <div class="container">
                    <div class="row">
                        <?php
                        foreach ($cat_children as $categoryc):
                            ?>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <a href="<?= $categoryc->getPermalink(); ?>">
                                    <div class="sekcja-oferta-obrazek" style="background-image:url('<?= $categoryc->getUrl(); ?>'); min-height:250px; background-size:cover;"></div>
                                    <h2 class="equalizer3 text-center">
                                        <?= $categoryc->name; ?>
                                    </h2>
                                </a>
                            </div>
                            <?php
                        endforeach;
                        ?>
                    </div>
                </div>
            </section>
        <?php } ?> 
    </div>
</div>
<section class="sekcja-produkty">
    <div class="sekcja-p">
        <div class="sekcja-c"> 
            <div class="sekcja-produkty-zaw">
                <div class="products container">
                    <div class="wrapper"> 
                        <?php if(!empty($products)): ?>
                        <div class="col-xs-12 col-sm-3">
                            <form method="POST" action="<?= site_url('wyszukiwarka'); ?>" id="a_filtering">
                                <nav class="filtry-box">
                                    <?php
                                    if (!empty($category)) {
                                        $categoryObj = new OfferCategoryModel($category->offer_category_id);
                                        if (!categoryHasChildren($category->offer_category_id) && !empty($categoryObj->parent_id)) {
                                            $categoryObj = new OfferCategoryModel($categoryObj->parent_id);
                                        }
                                        $categoryObjTrans = $categoryObj->getTranslation(LANG);
                                    }
                                    ?>
                                    <hr>
                                    <div class="s-title "><?= (new CustomElementModel('16'))->getField('moje filtry'); ?></div>
                                    <ul id="my_filters">
                                        <?php
                                        $filters = !empty($this->session->userdata['filters']) ? $this->session->userdata['filters'] : '';
                                        if (!empty($categories)) {
                                            foreach ($categories as $category1) {
                                                if ((!empty($category) && $category1->parent_id) == null || !empty($search)) {
                                                    $count_products = countCategoryProducts($category1->id);
                                                    if ($count_products > 0) {
                                                        if (!(empty($filters['category_id']) || (!empty($filters['category_id']) && in_array($category1->id, $filters['category_id'])))) {
                                                            continue;
                                                        }
                                                        ?>

                                                        <li group_list_id="kategoria" attr_id ="<?= $category1->id; ?>" class="my_cat_click">
                                                            <?= categoryHasChildren($category1->id) ? '' : ''; ?>
                                                                         <!--<a href="" class="category_link <?= $category1->id == $category->offer_category_id ? 'active' : 'no-active'; ?>" >-->

                                                            <!--</a>-->
                                                            <?= categoryHasChildren($category1->id) ? '' : ''; ?>
                                                            <label class="container-checkbox">

                                                                <?= $category1->name; ?> (<?= $count_products; ?>)
                                                                <input type="checkbox" name="category_id[]" value="<?= $category1->id; ?>" <?php
                                                                if (empty($filters['category_id']) || (!empty($filters['category_id']) && in_array($category1->id, $filters['category_id']))) {
                                                                    //if(categoryHasChildren($category->offer_category_id)){
                                                                    echo 'checked';
                                                                    //}
                                                                }
                                                                ?> class="search_go"/> 
                                                                <span class="checkmark"></span>

                                                            </label>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                        <!-- aktywne filtry -->
                                        <?php
                                        if (!empty($used_attr)) {
                                            if (!empty($groups)) {
                                                foreach ($groups as $group) {
                                                    $i = 0;
                                                    foreach ($group['attributes'] as $attr) {
                                                        if (!in_array($attr->id, $used_attr)) {
                                                            continue;
                                                        }
                                                        $i++;
                                                    }
                                                    if ($i == 0) {
                                                        continue;
                                                    }
                                                    if (!empty($group['attributes'])) {
                                                        foreach ($group['attributes'] as $attr) {
                                                            if (!in_array($attr->id, $used_attr)) {
                                                                continue;
                                                            }
                                                            if (!(!empty($filters['attributes']) && in_array($attr->id, $filters['attributes']))) {
                                                                continue;
                                                            }
                                                            ?>
                                                            <li attr_id="<?= $attr->id; ?>"  class="my_attr_click">
                                                                <label class="container-checkbox">
                                                                    <?= $attr->name; ?>
                                                                    <input type="checkbox" name="attributes[]" value="<?= $attr->id; ?>" <?php
                                                                    if (!empty($filters['attributes']) && in_array($attr->id, $filters['attributes'])) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?> class="search_go"/> 
                                                                    <span class="checkmark"></span>
                                                                </label>

                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </ul>
                                    <a href="#" id="clear_filters" class="text-right"><small>Wyczyść filtry</small></a>
                                    <div id="clear">

                                    </div>

                                    <div class="s-title "><?= (new CustomElementModel('16'))->getField('filtry'); ?></div>
                                    <div class="panel-group" id="accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" >
                                                        <h4 class=" margin33px">KATEGORIA</h4></a>
                                                </h4>
                                            </div>
                                            <div id="collapsekategorie" class="">
                                                <div class="panel-body">


                                                    <ul class="group_list" id="group_list_kategoria">
                                                        <?php
                                                        if (!empty($categories)) {
                                                            foreach ($categories as $category1) {
                                                                if ((!empty($category) && $category1->parent_id) == null || !empty($search)) {
                                                                    $count_products = countCategoryProducts($category1->id);
                                                                    if ($count_products > 0) {
                                                                        ?><a href="<?= $category1->getPermalink(); ?>">
                                                                            <li group_list_id="kategoria" attr_id ="k<?= $category1->id; ?>" destiny_url="<?= $category1->getPermalink(); ?>" class="cat_click">
                                                                                <?= categoryHasChildren($category1->id) ? '' : ''; ?>
                                                                                             <!--<a href="" class="category_link <?= $category1->id == $category->offer_category_id ? 'active' : 'no-active'; ?>" >-->

                                                                                <!--</a>-->
                                                                                <?= categoryHasChildren($category1->id) ? '' : ''; ?>
                                                                                <label class="container-checkbox">

                                                                                    <?= $category1->name; ?> (<?= $count_products; ?>)
                                                                                    <input type="checkbox" name="category_id[]" value="<?= $category1->id; ?>" <?php
                                                                                    if (empty($filters['category_id']) || (!empty($filters['category_id']) && in_array($category1->id, $filters['category_id']))) {
                                                                                        //if(categoryHasChildren($category->offer_category_id)){
                                                                                        echo 'checked';
                                                                                        //}
                                                                                    }
                                                                                    ?> class="search_go cat_id"/> 
                                                                                    <span class="checkmark"></span>

                                                                                </label>
                                                                            </li> </a>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </ul>

                                                </div>
                                            </div>

                                        </div>
                                        <?php
                                        if (!empty($used_attr)) {
                                            if (!empty($groups)) {
                                                foreach ($groups as $group) {
                                                    $i = 0;
                                                    foreach ($group['attributes'] as $attr) {
                                                        if (!in_array($attr->id, $used_attr)) {
                                                            continue;
                                                        }
                                                        $i++;
                                                    }
                                                    if ($i == 0) {
                                                        continue;
                                                    }
                                                    ?>
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a data-toggle="collapse">
                                                                    <h4 class=" margin33px"><?= $group['group']->name; ?></h4></a>
                                                            </h4>
                                                        </div>
                                                        <div id="a_<?= $group['group']->name; ?>" >
                                                            <div class="panel-body">


                                                                <ul class="group_list " id="group_list_<?= $group['group']->id; ?>">
                                                                    <?php
                                                                    if (!empty($group['attributes'])) {
                                                                        $i = 0;
                                                                        foreach ($group['attributes'] as $attr) {
                                                                            if (!in_array($attr->id, $used_attr)) {
                                                                                continue;
                                                                            }
                                                                            $i++;
                                                                            ?>
                                                                            <li <?= $i > 4 ? 'class="more more_' . $group['group']->id . '"' : ''; ?> group_list_id="<?= $group['group']->id; ?>" attr_id="<?= $attr->id; ?>">
                                                                                <label class="container-checkbox">
                                                                                    <?= $attr->name; ?>
                                                                                    <input type="checkbox" name="attributes[]" value="<?= $attr->id; ?>" <?php
                                                                                    if (!empty($filters['attributes']) && in_array($attr->id, $filters['attributes'])) {
                                                                                        echo 'checked';
                                                                                    }
                                                                                    ?> class="search_go attr_id"/> 
                                                                                    <span class="checkmark"></span>
                                                                                </label>

                                                                            </li>
                                                                            <?php
                                                                        }
                                                                        if ($i > 4) {
                                                                            ?>
                                                                            <li>
                                                                                <span class="read_more" all="0" group_id="<?= $group['group']->id; ?>">
                                                                                    <?= (new CustomElementModel('9'))->getField('wiecej'); ?>
                                                                                </span>
                                                                            </li>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ul>

                                                            </div>
                                                        </div>

                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        ?>

                                        <hr>
                                        <div class="form-group margin33px">
                                            <input type="submit" value="<?= (new CustomElementModel('9'))->getField('zobacz'); ?>" class="btn btn-primary btn-block" id="zobacz_button" style="visibility:hidden;"/>
                                        </div>
                                    <?php } ?>
                                </nav>

                            </form>
                            <form method="GET" action="<?= site_url('wyszukiwarka'); ?>" id="alter_search">
                                <input type="hidden" name="categories" value="<?= !empty($_GET['categories']) ? $_GET['categories'] : ''; ?>" id="alter_categories"/>
                                <input type="hidden" name="attributes" value="<?= !empty($_GET['attributes']) ? $_GET['attributes'] : ''; ?>" id="alter_attributes"/>
                                <input type="hidden" name="str" value="<?= !empty($_GET['str']) ? $_GET['str'] : ''; ?>" id="alter_str"/>
                            </form>

                        </div> 
                        <?php endif;?>
                        <div class="col-xs-12 col-sm-9">
                            <div class="boxes-1">
                                <div class="row row10" id="list-container">
                                    <?php
                                    $this->load->view('Oferta/search_result', [
                                        'products' => $products,
                                        'categories' => $categories,
                                        'search' => 1
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        //czyszczenie filtrów
        $("#clear_filters").click(function (e) {
            e.preventDefault();
            $('.cat_id').prop('checked', false);
            $('.attr_id').prop('checked', false);
            search_go();
            $('#alter_search').submit();
        });

        //odklik kategorii
        $('.my_cat_click').click(function () {
            var id = $(this).attr('attr_id');
            $(".cat_id[value='" + id + "']").prop('checked', false);
            $(this).find('input').prop('checked', false);
            //$(this).remove();
            $('#alter_search').submit();
        });
        //odklik atrybutu
        $('.my_attr_click').click(function () {
            var id = $(this).attr('attr_id');
            $(".attr_id[value='" + id + "']").prop('checked', false);
            $(this).find('input').prop('checked', false);
            //$(this).remove();
            console.log('action');
            $('#alter_search').submit();
            return 1;
        });
        //wybór kategorii
        $('.cat_click').click(function () {
            search_go();
            $('#alter_search').submit();
        });

        search_go();
        $('#zobacz_button').css('display', 'none');
        $('#szukajka').keyup(function () {
            search_go();
        });
        $('.search_go').click(function () {
            search_go();
            $('#alter_search').submit();
        });
        //mniej więcej
        $('.read_more').click(function () {
            var group_id = $(this).attr('group_id');
            var all = $(this).attr('all');
            if (all == 0) {
                $('.more_' + group_id).css('display', 'block');
                $(this).attr('all', 1);
                $(this).html("<?= (new CustomElementModel('9'))->getField('mniej'); ?>");
            } else {
                $('.more_' + group_id).css('display', 'none');
                $(this).attr('all', 0);
                $(this).html("<?= (new CustomElementModel('9'))->getField('wiecej'); ?>");
            }

        });
    });

    function search_go() {

        ///my_filters();
        var str = $('#szukajka').val();
        var categories = '';
        var category_id = $(".cat_id").map(function () {
            if ($(this).is(':checked')) {
                var valu = $(this).val();
                categories += valu + '_';
                return $(this).val();
            }
        }).get();
        //category_id.push('<?= !empty($category) ? $category->offer_category_id : 'NULL'; ?>');

        var attributes_str = '';
        var attributes = $(".attr_id:checked").map(function () {
            attributes_str += $(this).val() + '_';
            return $(this).val();
        }).get();

        if (categories === '') {
            categories = '0';
        }

        $('#alter_categories').val(categories.substring(0, categories.length - 1));
        $('#alter_attributes').val(attributes_str.substring(0, attributes_str.length - 1));
        $('#alter_str').val(str);
    }


</script>