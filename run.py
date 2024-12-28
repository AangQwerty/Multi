import os
from tools import license

class Run:

	def Masuk(self):
		if "linux" in sys.platform.lower():os.system("clear")
		elif "win" in sys.platform.lower():os.system("cls")
		else:os.system("clear")

if __name__=="__main__":
	license.CheckLicense().Check()
