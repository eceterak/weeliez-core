<?php if(isset($verification) && $verification === true): ?>
	<div>Email address verified. You can now log in.</div>
<?php else: ?>
	<div>There is a problem verificating your email address. Please contact the administration.</div>
<?php endif; ?>