<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Webloyer</title>

    <link href="{{ asset('vendor/webloyer/css/app.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fork-awesome@1.1.7/css/fork-awesome.min.css" integrity="sha256-gsmEoJAws/Kd3CjuOQzLie5Q3yshhvmo7YNtBG7aaEY=" crossorigin="anonymous">
    <link href="{{ asset('vendor/multi-select/css/multi-select.css') }}" rel="stylesheet">
    <style>
        html,
        body {
          height: 100%;
        }

        #wrap {
            min-height: 100%;
            height: auto;
            margin: 0 auto -120px;
            padding: 0 0 120px;
        }

        footer {
            height: 120px;
            background-color: #e7e7e7;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div id="wrap">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ url('/') }}">Webloyer</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    @if (!Auth::guest())
                        <ul class="nav navbar-nav">
                            <li><a href="{{ url('/projects') }}">Projects</a></li>
                            @if (Auth::user()->hasPermission('view.recipe'))
                                <li><a href="{{ url('/recipes') }}">Recipes</a></li>
                            @endif
                            @if (Auth::user()->hasPermission('view.server'))
                                <li><a href="{{ url('/servers') }}">Servers</a></li>
                            @endif
                            @if (Auth::user()->hasPermission('view.user'))
                                <li><a href="{{ url('/users') }}">Users</a></li>
                            @endif
                        </ul>
                    @endif

                    <ul class="nav navbar-nav navbar-right">
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="javascript:document.getElementById('form-logout').submit()">Logout</a>
                                        {!! Form::open(['url' => url('/logout'), 'id' => 'form-logout']) !!}
                                        {!! Form::close() !!}
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    {!! Breadcrumbs::exists() ? Breadcrumbs::render() : '' !!}
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <footer>
        <div class="container text-center">
            <p class="text-muted credit">
                <p><a href="https://github.com/ngmy/webloyer"><i class="fa fa-github fa-2x" aria-hidden="true" style="color: white;"></i></a></p>
                <p>&copy; 2015 Yuta Nagamiya.</p>
                <p>Powered by <a href="https://deployer.org/" target="_blank">Deployer</a>.</p>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('vendor/webloyer/js/app.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="//cdn.jsdelivr.net/clipboard.js/1.5.3/clipboard.min.js"></script>
    <script src="{{ asset('vendor/multi-select/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('vendor/ace/js/ace.js') }}"></script>
    <script>
        // Hook up ACE editor to all textareas with data-editor attribute
        $(function () {
            $('textarea[data-editor]').each(function () {
                var textarea = $(this);

                var mode = textarea.data('editor');

                var editDiv = $('<div>', {
                    position: 'absolute',
                    width: textarea.closest('div').width(),
                    height: textarea.closest('div').height(),
                    'class': textarea.attr('class')
                }).insertBefore(textarea);

                textarea.css('display', 'none');

                var editor = ace.edit(editDiv[0]);
                editor.renderer.setShowGutter(false);
                editor.getSession().setValue(textarea.val());
                editor.getSession().setMode('ace/mode/' + mode);
                editor.setTheme('ace/theme/github');

                // copy back to textarea on form submit...
                textarea.closest('form').submit(function () {
                    textarea.val(editor.getSession().getValue());
                })

            });
        });
    </script>
    <script>
        $(function () {
            $('form').submit(function () {
                $(this).find(':submit').attr('disabled', 'disabled');
            });
        });
    </script>
    <script>
        // Pre-selected
        $(function () {
            $('.ms-container').each(function (i, elem) {
                var orgId = $(elem).attr('id').replace(/^ms\-/, '');
                var orderId = orgId + '_order';
                var getVal = $('#' + orderId).val();
                var getValArray = getVal.split(',');
                var newValArray = getValArray.filter(function (x) {
                    return x != '';
                });
                var selectableIdArray = newValArray.map(function (x) {
                    var index = $('#' + orgId + ' option').index($('option[value="' + x + '"]'));
                    var id = $('.ms-elem-selectable').eq(index).attr('id');
                    return id;
                });
                var selectionIdArray = selectableIdArray.map(function (x) {
                    return x.replace(/selectable/, 'selection');
                });
                var ul = $('.ms-selection ul');
                selectionIdArray.forEach(function (x) {
                    var li = ul.find('#' + x);
                    ul.append(li);
                });
            });
        });
    </script>
    <script>
        // To sortable selected list
        $(function () {
            $('.ms-selection ul').sortable({
                update: function (event, ui) {
                    var sortableIdArray = $(this).sortable('toArray');
                    var selectionIdArray = sortableIdArray.filter(function (x) {
                        return $('#' + x).is(':visible');
                    });
                    var selectableIdArray = selectionIdArray.map(function (x) {
                        return x.replace(/selection/, 'selectable');
                    });
                    var orgId = $(this).closest('.ms-container').attr('id').replace(/^ms\-/, '');
                    var orderId = orgId + '_order';
                    var orgValArray = selectableIdArray.map(function (x) {
                        var index = $('.ms-elem-selectable').index($('#' + x));
                        var orgVal = $('#' + orgId + ' option').eq(index).val();
                        return orgVal;
                    });
                    var newVal = orgValArray.join(',');
                    $('#' + orderId).val(newVal);
                }
            });
        });
    </script>
    <script>
        // Multiple select
        $('.multi-select').multiSelect({
            keepOrder: true,
            afterSelect: function (value) {
                var orderId = this.$container.attr('id').replace(/^ms\-/, '') + '_order';
                var getVal = $('#' + orderId).val();
                var getValArray = getVal.split(',');
                getValArray.push(value);
                var newValArray = getValArray.filter(function (x) {
                    return x != '';
                });
                var newVal = newValArray.join(',');
                $('#' + orderId).val(newVal);
            },
            afterDeselect: function (value) {
                var orderId = this.$container.attr('id').replace(/^ms\-/, '') + '_order';
                var getVal = $('#' + orderId).val();
                var getValArray = getVal.split(',');
                var newValArray = getValArray.filter(function (x) {
                    return x != value && x != '';
                });
                var newVal = newValArray.join(',');
                $('#' + orderId).val(newVal);
            }
        });
    </script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script>
        $(function () {
            var clipboard = new Clipboard('.btn');

            clipboard.on('success', function (e) {
                e.clearSelection();
            });
        });
    </script>
</body>
</html>
