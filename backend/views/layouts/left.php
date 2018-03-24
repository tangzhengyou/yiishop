<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>./../../images/user.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=\Yii::$app->user->identity->username?></p>
                <i class="fa fa-circle text-success"></i>
                <a href="#">

                    <?=Yii::$app->user->isGuest?"Offline":"Online"?></a>
            </div>
        </div>

        <!-- search form -->

        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    [
                        'label' => '管理员管理',
                        'icon' => 'user-circle-o  ',
                        'url' => '#',
                        'items' => [
                            ['label' => '管理员列表', 'icon' => 'list-ul', 'url' => ['admin/index'],],
                            ['label' => '添加管理员', 'icon' => 'plus-square-o', 'url' => ['admin/add'],],

                        ],
                    ],[
                        'label' => 'RBAC',
                        'icon' => 'user-circle-o  ',
                        'url' => '#',
                        'items' => [
                            ['label' => '权限列表', 'icon' => 'list-ul', 'url' => ['permission/index'],],
                            ['label' => '添加权限', 'icon' => 'plus-square-o', 'url' => ['permission/add'],],
                            [
                                'label' => '角色管理',
                                'icon' => 'book',
                                'url' => '#',
                                'items' => [
                                    ['label' => '角色列表', 'icon' => 'list-ul', 'url' => ['role/index'],],
                                    ['label' => '角色添加', 'icon' => 'plus-square-o', 'url' => ['role/add'],],

                                ],
                            ],
                        ],
                    ],
                    [
                        'label' => '品牌管理',
                        'icon' => 'envira ',
                        'url' => '#',
                        'items' => [
                            ['label' => '品牌列表', 'icon' => 'list-ul', 'url' => ['brand/index'],],
                            ['label' => '添加品牌', 'icon' => 'plus-square-o', 'url' => ['brand/add'],],

                        ],
                    ],
                    [
                        'label' => '文章管理',
                        'icon' => 'book ',
                        'url' => '#',
                        'items' => [
                            ['label' => '文章列表', 'icon' => 'list-ul', 'url' => ['article/index'],],
                            ['label' => '添加文章', 'icon' => 'plus-square-o', 'url' => ['article/add'],],
                            [
                                'label' => '文章分类',
                                'icon' => 'book',
                                'url' => '#',
                                'items' => [
                                    ['label' => '文章分类列表', 'icon' => 'list-ul', 'url' => ['/article-category/index'],],
                                    ['label' => '文章分类添加', 'icon' => 'plus-square-o', 'url' => ['/article-category/add'],],

                                ],
                            ],

                        ],
                    ],
                    [
                        'label' => '商品类管理',
                        'icon' => 'lock',
                        'url' => '#',
                        'items' => [
                            ['label' => '类列表', 'icon' => 'list-ul', 'url' => ['category/index'],],
                            ['label' => '添加类', 'icon' => 'plus-square-o', 'url' => ['category/add'],],


                        ],
                    ],
                    [
                        'label' => '商品管理',
                        'icon' => 'gift ',
                        'url' => '#',
                        'items' => [
                            ['label' => '商品分类列表', 'icon' => 'list-ul', 'url' => ['goods/index'],],
                            ['label' => '添加商品分类', 'icon' => 'plus-square-o', 'url' => ['goods/add'],],


                        ],
                    ],
//                    ['label' => '', 'icon' => 'file-code-o', 'url' => ['/gii']],
//                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
//                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
//                    [
//                        'label' => 'Some tools',
//                        'icon' => 'share',
//                        'url' => '#',
//                        'items' => [
//                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
//                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
//
//                        ],
//                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
