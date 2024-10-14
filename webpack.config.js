/**
 * WordPress Dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

/**
 * Local dependencies
 */
const path = require( 'path' );
const MiniCSSExtractPlugin = require( 'mini-css-extract-plugin' );
const FixStyleOnlyEntriesPlugin = require( 'webpack-fix-style-only-entries' );
const CopyPlugin = require( 'copy-webpack-plugin' );
const postcssPresetEnv = require( 'postcss-preset-env' );

module.exports = [
	{
		...defaultConfig,
		...{
			entry: {
				admin: path.resolve( process.cwd(), 'src/js', 'admin.js' ),
				block: path.resolve( process.cwd(), 'src/js', 'block.js' ),
				bundle: path.resolve( process.cwd(), 'src/js', 'bundle.js' ),
			},
			output: {
				filename: '[name].min.js',
				path: path.resolve( __dirname, 'assets/scripts' ),
			},
		},
	},
	{
		mode: 'production',
		entry: {
			'bundle-style': path.resolve(
				process.cwd(),
				'src/scss',
				'bundle.scss'
			),
			'admin-style': path.resolve(
				process.cwd(),
				'src/scss',
				'admin.scss'
			),
		},
		output: {
			filename: '[name].js',
			path: path.resolve( process.cwd(), 'assets', 'css' ),
		},
		module: {
			rules: [
				{
					test: /\.(sa|sc|c)ss$/,
					use: [
						MiniCSSExtractPlugin.loader,
						{
							loader: 'css-loader',
							options: {
								url: false,
								sourceMap: true,
								importLoaders: 2,
							},
						},
						{
							loader: 'postcss-loader',
							options: {
								sourceMap: true,
								postcssOptions: {
									plugins: () => [
										postcssPresetEnv( {
											stage: 3,
											browsers: 'last 2 versions',
											autoprefixer: {
												flexbox: 'no-2009',
											},
										} ),
									],
								},
							},
						},
						{
							loader: 'sass-loader',
							options: {
								sourceMap: true,
								sassOptions: {
									outputStyle: 'compressed',
								},
							},
						},
					],
				},
			],
		},
		plugins: [
			new FixStyleOnlyEntriesPlugin(),
			new MiniCSSExtractPlugin( {
				filename: '[name].min.css',
			} ),
			new CopyPlugin( {
				patterns: [
					{
						from: 'src/img',
						to: path.resolve( process.cwd(), 'assets', 'img' ),
						noErrorOnMissing: true,
					},
					{
						from: 'src/fonts',
						to: path.resolve( process.cwd(), 'assets', 'fonts' ),
						noErrorOnMissing: true,
					},
				],
			} ),
		],
	},
];
