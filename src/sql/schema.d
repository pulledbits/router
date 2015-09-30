module sql.schema;

import sql.table;

import d2sqlite3;

class Schema {
	private Database sqlite_database;
	
	public this() {
	}
	
	/**
	 * SQLite constructor
	 */
	public this(Database database) {
		this.sqlite_database = database;
	}
	
	public Table table(string tableIdentifier) {
		return new Table(tableIdentifier);
	}
	
	unittest {
		import dunit.toolkit;
		
		Schema teach = new Schema();
		Table studentgroep = teach.table("studentgroep");
	}
	
	static sqlite(string path) {
		return new Schema(Database(path));
	}
	
	unittest {
		import dunit.toolkit;
		
		Schema teach = Schema.sqlite("/tmp/database.sqlite3");
		assertEqual("/tmp/database.sqlite3", teach.sqlite_database.attachedFilePath());
		Table studentgroep = teach.table("studentgroep");
	}
}