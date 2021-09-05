# moodle_local_elggsync
Event-drived Moodle-Elgg integration with some task included 

Clone the repository inside your moodle/local fouder.
Rename the clone fouder to elggsync (because moodle needs plugin name consistency).

Install the plugin using moodle system.

On Elgg. You need yo install the Elgg plugin for integration ('https://github.com/davisonajr/elgg_moodle_integration.git')
Create a webservice API key to use in moodle

On Moodle local administration, Enables Elgg-moodle integration, insert the path in which your Elgg is installed inside your Moodle foulder and then paste the API key you created in Elgg.

After that configurations, all users created in moodle will be created in Elgg as well.

In Future there will be integration on the other direction (from Elgg to Moodle).
