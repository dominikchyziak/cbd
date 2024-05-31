.gallery_module{
background-size: <?= $background_size;?>;
background-position: center;
background-repeat: no-repeat;
height: 200px;
}
.gallery_module_1{
height: <?= $module1_height;?>;
}
.gallery_module_2{
height: <?= $module2_height;?>;
}
.gallery_module_3{
height: <?= $module3_height;?>;
}
.gallery_module_4{
height: <?= $module4_height;?>;
}
.gallery_module_3_big{
height: <?= $module3_big_height;?>;
}
.gallery_module_3_small{
height: <?= ($module3_big_height-($module3_vertical_separator))/2.0; ?>px;
}
.gallery_module_3_small_top{
margin-bottom: <?= $module3_vertical_separator;?>;
}
.gallery_module_3_small_bottom{
margin-top: <?= $module3_vertical_separator;?>;
}
.gallery_module_separator{
margin-bottom: <?= $margin_vertical; ?>;
}

<?= $extras; ?>

@media (max-width: 520px){
.wow{
animation-delay: 0s!important;
}
.gallery_module_3_small_top{
margin-bottom: 0;
}
.gallery_module_3_small_bottom{
margin-top: 0;
}
.gallery_module_separator{
margin-bottom: 0;
}

.gallery_module{
height: 250px;
margin: 10px 0;
}

}