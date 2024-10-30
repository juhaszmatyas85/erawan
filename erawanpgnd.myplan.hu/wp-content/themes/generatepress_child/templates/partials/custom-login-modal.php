<div class="hystmodal" id="loginModal" aria-hidden="true">
	<div class="hystmodal__wrap">
		<div class="hystmodal__window">
			<form action="<?php echo wp_login_url(); ?>" method="post">
				<div class="hystmodal__header">
					<h2><?php echo esc_html__( 'Felhasználói fiók', 'erawan' ); ?></h2>
					<button class="hystmodal__close" data-hystclose aria-label="Close modal">×</button>
				</div>
				<div class="hystmodal__body">
					<p><?php echo esc_html__( 'A bejelentkezéshez add meg az alábbi adataidat.', 'erawan' ); ?></p>
					<div class="form">
						<label>
							<input class="w-100" type="email" name="log" placeholder="<?php echo esc_attr__( 'Email cím', 'erawan' ); ?>" required>
						</label>
						<label>
							<input class="w-100" type="password" name="pwd" placeholder="<?php echo esc_attr__( 'Jelszó', 'erawan' ); ?>" required>
						</label>
						<label>
							<input type="checkbox" name="rememberme" value="forever"> <?php echo esc_html__( 'Emlékezz rám', 'erawan' ); ?>
						</label>
						<p>
							<?php echo esc_html__( 'Nem rendelkezel még felhasználóval?', 'erawan' ); ?> <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php echo esc_html__( 'Regisztrálj az oldalra.', 'erawan' ); ?></a><br>
							<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php echo esc_html__( 'Elfelejtetted a jelszavadat?', 'erawan' ); ?></a>
						</p>
					</div>
				</div>
				<div class="hystmodal__footer">
					<button data-hystclose><?php echo esc_html__( 'Mégsem', 'erawan' ); ?></button>
					<button type="submit"><?php echo esc_html__( 'Bejelentkezés', 'erawan' ); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>
