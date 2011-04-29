<phpunit backupGlobals="false" backupStaticAttributes="true" bootstrap="project/apps/{{ projectName }}/tests/unit/conf/bootstrap.php" colors="true" convertErrorsToExceptions="true" convertNoticesToExceptions="true" convertWarningsToExceptions="true" processIsolation="true" stopOnFailure="true" syntaxCheck="false" testSuiteLoaderClass="PHPUnit_Runner_StandardTestSuiteLoader">
	<testsuites>
		<testsuite name="Project: {{ projectName }} - Test Suite">
			<directory>project/apps/{{ projectName }}/tests/unit/lib</directory>
		</testsuite>
	</testsuites>
</phpunit>