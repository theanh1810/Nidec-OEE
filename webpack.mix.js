const mix = require('laravel-mix');
// mix
// .js('resources/js/app.js', 'public/js')
// .postCss('resources/css/app.css', 'public/css', [
//     require('postcss-import'),
//     require('tailwindcss'),
//     require('autoprefixer'),
// ]);

mix.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.DefinePlugin({
                'process.env': {
                    SOCKET_PORT: JSON.stringify(process.env.SOCKET_PORT || '3000'),
                    SOCKET_HOST: JSON.stringify(process.env.SOCKET_HOST || 'localhost'),
                }
            })
        ]
    }
})

mix
    .js('resources/js/app.js', 'public/js')
    .sass('resources/scss/app.scss', 'public/css')
mix
    .js('resources/js/master-data/unit.js', 'public/js/master-data')
    .js('resources/js/master-data/status.js', 'public/js/master-data')
    .js('resources/js/master-data/shift.js', 'public/js/master-data')
    .js('resources/js/master-data/product.js', 'public/js/master-data')
    .js('resources/js/master-data/materials.js', 'public/js/master-data')
    .js('resources/js/master-data/machine.js', 'public/js/master-data')
    .js('resources/js/master-data/holiday.js', 'public/js/master-data')
    .js('resources/js/master-data/mold.js', 'public/js/master-data')
    .js('resources/js/master-data/agv.js', 'public/js/master-data')
    .js('resources/js/production-plan/command.js', 'public/js/production-plan')
    .js('resources/js/production-plan/detail.js', 'public/js/production-plan')
    .js('resources/js/warehouse-system/export-materials.js', 'public/js/warehouse-system')
    .js('resources/js/account/user.js', 'public/js/account')
    .js('resources/js/account/role.js', 'public/js/account')
    .js('resources/js/efficiencyAGV.js', 'public/js')
    .js('resources/js/control_agv/list_command.js', 'public/js/control_agv')
    // .js('resources/js/control_agv/control/agvControl.js', 'public/js/control_agv/control/')
    // .js('resources/js/control_agv/control/imgMap.js', 'public/js/control_agv/control/')
    // .js('resources/js/control_agv/control/imgTheme.js', 'public/js/control_agv/control/')
    // .js('resources/js/control_agv/control/pointMap.js', 'public/js/control_agv/control/')
    // .js('resources/js/control_agv/control/lineMap.js', 'public/js/control_agv/control/')
    // .js('resources/js/control_agv/control/imgAgv.js', 'public/js/control_agv/control/')
    // .js('resources/js/control_agv/control/point.js', 'public/js/control_agv/control/')
    // .js('resources/js/control_agv/control/line.js', 'public/js/control_agv/control/')
    // .js('resources/js/control_agv/control/lineHide.js', 'public/js/control_agv/control/')
    .js('resources/js/master-data/line.js', 'public/js/master-data')

mix
.js('resources/js/oee/visualization.js', 'public/js/oee')
.js('resources/js/oee/report.js', 'public/js/oee')
.js('resources/js/oee/detail.js', 'public/js/oee').react()

// mix
// .js('resources/js/dashboard.js', 'public/js').react()

// mix
// .setPublicPath('node_server/')
// .js('resources/js/node_server/monitor.js', 'public/js')
// .js('resources/js/node_server/calculator.js', 'public/js').react()
