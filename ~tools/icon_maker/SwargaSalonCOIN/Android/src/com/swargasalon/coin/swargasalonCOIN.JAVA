package com.swargasalon.coin;
import android.os.Bundle;
import android.view.Menu;
import org.apache.cordova.*;

import com.swargasalon.coin.R;

public class SwargaSalonCOIN extends DroidGap {
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
        super.init();
        super.appView.clearCache(true);
        super.loadUrl("file:///android_asset/www/index.html",3000); 
	}
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}

}
