<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://virtualizate.com.co
 * @since      1.0.0
 *
 * @package    STS
 * @subpackage STS/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    STS
 * @subpackage STS/admin
 * @author     Sergio Rondón | Grupo Virtualizate <soporte@virtualizate.com.co>
 */
class STS_Admin {
	/**
	 * Undocumented variable
	 *
	 * @var [type]
	 */
	private $loader;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $sts    The ID of this plugin.
	 */
	private $sts;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $account = '10012716'; //número de usuario
	private $apiKey = 'Ch14SSCQmLQcneUtkTc5rLsE762Szh'; //clave API del usuario
	private $token = 'be74f3e9d0069f54104ac5704a4d4897'; // Token de usuario

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $sts       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $sts, $version ) {

		$this->sts = $sts;
		$this->version = $version;
		$this->init();
		$this->run();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in STS_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The STS_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->sts, plugin_dir_url( __FILE__ ) . 'css/sts-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in STS_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The STS_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->sts, plugin_dir_url( __FILE__ ) . 'js/sts-admin.js', array( 'jquery' ), $this->version, false );

	}

	/* public function sts_send_sms()
	 * {
	 *	$ch = curl_init();
     *
	 *	$account = $this->account;
	 *	$apiKey = $this->apiKey;
	 *	$token = $this->token;
     *
	 *	$post = array(
	 *		'account' => $account, //número de usuario
	 *		'apiKey' => $apiKey, //clave API del usuario
	 *		'token' => $token, // Token de usuario
	 *		'toNumber' => '573107706620', //número de destino
	 *		'sms' => 'SMS de prueba Hablame desde plugin Shippment Tracking SMS' , // mensaje de texto
	 *		'flash' => '0', //mensaje tipo flash
	 *		'sendDate'=> time(), //fecha de envío del mensaje
	 *		'isPriority' => 0, //mensaje prioritario
	 *		'sc'=> '899991', //código corto para envío del mensaje de texto
	 *		'request_dlvr_rcpt' => 0, //mensaje de texto con confirmación de entrega al celular
	 *	);
     * 
	 *	$url = "https://api101.hablame.co/api/sms/v2.1/send/"; //endPoint: Primario
     *
	 *	curl_setopt ($ch,CURLOPT_URL,$url) ;
	 *	curl_setopt ($ch,CURLOPT_POST,1);
	 *	curl_setopt ($ch,CURLOPT_POSTFIELDS, $post);
	 *	curl_setopt ($ch,CURLOPT_RETURNTRANSFER, true);
	 *	curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT ,3);
	 *	curl_setopt ($ch,CURLOPT_TIMEOUT, 20);
	 *	$response = curl_exec($ch);
	 *	curl_close($ch);
	 *	$response = json_decode($response ,true) ;
     *
	 *	//La respuesta estará alojada en la variable $response
     *
	 *	if ($response["status"]== '1x000' ){
	 *	echo 'El SMS se ha enviado exitosamente con el ID: '.$response["smsId"].PHP_EOL;
	 *	} else {
	 *	echo 'Ha ocurrido un error:'.$response["error_description"].'('.$response ["status" ]. ')'. PHP_EOL;
	 *	}
	 * }
	 */

	public function init()
	{
		load_plugin_textdomain( STS_SLUG, false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

		if (!class_exists('WooCommerce')) {
			add_action('admin_notices', [$this, 'woocommerce_sts_missing_wc_notice']);
			return;
		}

		$GLOBALS['STS'] = $this->shipping_tracking_sms();
	}

	/**
	 * WooCommerce fallback notice.
	 *
	 * @since 1.6.20
	 * @return void
	 */
	public function woocommerce_sts_missing_wc_notice() {
		/* translators: %s WC download URL link. */
		echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Shipping Tracking SMS requires WooCommerce to be installed and active. You can download %s here.', 'woocommerce-shipment-tracking' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
	}

	/**
	 * Returns an instance of STS.
	 *
	 * @since 1.6.5
	 * @version 1.6.5
	 *
	 * @return STS
	 */
	public function shipping_tracking_sms() {
		static $instance;

		if ( ! isset( $instance ) ) {
			$instance = new STS();
		}

		return $instance;
	}


	public function run()
	{
		//$this->sts_send_sms();
		//$this->loader->run();
	}

}
